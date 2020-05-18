<?php

namespace App\DataFixtures;

use App\Entity\Tab;
use App\Entity\Cours;
use App\Entity\Ligne;
use App\Entity\Exercice;
use App\Entity\Solution;
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

                $value="";
                for ($k = 0; $k<10;$k++) {
                    $value.="Ligne $k\n";
                }

                $exo->initExercice($value, "Test des consignes", $manager);

                $manager->persist($exo);
                $cour->addExercice($exo);
            }
            $manager->persist($cour);
        }

        $manager->flush();
    }
}
