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
                $exo->setConsigne("Test des consignes");
                $solution = new Solution();
                for ($k = 0; $k<10;$k++) {
                    $ligne = new Ligne();
                    $ligne->setText("Ligne xxxxxx");
                    $manager->persist($ligne);
                    $exo->addIdLigne($ligne);
                    $tab = new Tab();
                    $tab->setNbTab($k);
                    $tab->setIdLigne($ligne);
                    $manager->persist($tab);
                    $solution->addIdTab($tab);
                }
                $manager->persist($solution);
                $exo->addIdSolution($solution);
                $manager->persist($exo);
                $cour->addExercice($exo);
            }
            $manager->persist($cour);
        }

        $manager->flush();
    }
}
