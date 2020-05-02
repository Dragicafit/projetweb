<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Cours;

class CoursFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <=10; $i++) {
            $cour = new Cours();
            $cour->setTitle('Boucle While ('.$i.')')
                ->setAuteur('Jean-Michel')
                ->setTemps(15)
                ->setValue(["While(value == true){","if(age>=18){","print(\"Vous êtes majeurs\");","}else{","print(\"Vous êtes mineur\");","}","}"]);
            $manager->persist($cour);
        }

        $manager->flush();
    }
}
