<?php

namespace App\DataFixtures;

use App\Entity\Cours;
use App\Entity\Exercice;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CoursFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <=10; $i++) {
            $cour = new Cours();
            $cour->setTitle('Boucle While ('.$i.')')
                ->setAuteur('Jean-Michel')
                ->setTemps(15);
            for ($j = 1; $j<=5; $j++) {
                $exo = new Exercice();
                $exo->setExo(["int i = 1;", "While(i < 10){" , "print(\"Salut c'est la \".val.\"eme fois que tu me vois !\");","i++;","} "]);
                $exo->setConsigne("Test des consignes");
                $manager->persist($exo);
                $cour->addExercice($exo);
            }
            $manager->persist($cour);
        }

        $manager->flush();
    }
}
