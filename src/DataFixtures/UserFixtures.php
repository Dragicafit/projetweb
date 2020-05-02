<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i<10; $i++) {
            $user = new Users();
            $user->setEmail('user'.$i.'@email.com')
                ->setUsername('user'.$i)
                ->setPassword('jesuisunuser')
                ->setNom('User'.$i)
                ->setPrenom('UserP'.$i);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
