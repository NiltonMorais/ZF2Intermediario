<?php

namespace SONUser\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\Persistence\ObjectManager;

use SONUser\Entity\User;

class LoadUser extends AbstractFixture{
    
    public function load(ObjectManager $manager) {
        $user = new User();
        $user->setNome("Nilton")
                ->setEmail("nilton.morais@avatim.com.br")
                ->setPassword("123456")
                ->setActive(true);
        
        $manager->persist($user);
        
        $user = new User();
        $user->setNome("Admin")
                ->setEmail("admin@teste.com")
                ->setPassword("123456")
                ->setActive(true);
        
        $manager->persist($user);
        
        $manager->flush();        
    }

}