<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $userP = new User();
        $userP->setPrenom("Prof")
        ->setNom("Prof")
        ->setEmail("prof@prof.com")
        ->setUsername("UserP")
        ->setPassword('testtest')
        ->setProf(true);

        $manager->persist($userP);

        $userE = new User();
        $userE->setPrenom("Eleve")
        ->setNom("Eleve")
        ->setEmail("eleve@eleve.com")
        ->setUsername("UserE")
        ->setPassword('testtest')
        ->setProf(false);

        $manager->persist($userP);

        $manager->flush();
    }
}
