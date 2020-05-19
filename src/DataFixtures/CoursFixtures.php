<?php

namespace App\DataFixtures;

use App\Entity\Tab;
use App\Entity\User;
use App\Entity\Cours;
use App\Entity\Ligne;
use App\Entity\Exercice;
use App\Entity\Solution;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CoursFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $repo = $manager->getRepository(User::class);
        for ($i = 1; $i <=10; $i++) {
            $cour = new Cours();
            $cour->setTitle("Boucle While ($i)")
                ->setAuteur($repo->findOneBy(['username' => "UserP"]))
                ->setTemps(15);
            $manager->persist($cour);

            for ($j = 1; $j<=5; $j++) {
                $exo = new Exercice();

                $value="";
                for ($k = 0; $k<5;$k++) {
                    $value.="  Ligne $k\n";
                }
                for ($k = 0; $k<5;$k++) {
                    $value.=" Ligne $k\n";
                }
                $cour->addExercice($exo);

                $exo->initExercice($value, "Test des consignes", $manager);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ UserFixtures::class ];
    }
}
