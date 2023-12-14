<?php

namespace FC\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FC\UserBundle\Entity\User;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadUser implements FixtureInterface, ContainerAwareInterface {

    private $container;

    public function load(ObjectManager $manager) {
        $user = new User();
        $user->setUsername("user")
            ->setEmail("user@user.com")
            ->setPassword($this->encodePassword($user,"user"))
            ->setRoles(array("ROLE_USER"))
            ->setIsActive(true);
        
        $manager->persist($user);

        $admin = new User();
        $admin->setUsername("admin")
            ->setEmail("admin@admin.com")
            ->setPassword($this->encodePassword($admin,"admin"))
            ->setRoles(array("ROLE_ADMIN"))
            ->setIsActive(true);
        
        $manager->persist($admin);

        $manager->flush();
    }
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    private function encodePassword($user, $plainPassword) {
        $encoder = $this->container->get("security.encoder_factory")
        ->getEncoder($user);

        return $encoder->encodePassword($plainPassword, $user->getSalt());
    }

}