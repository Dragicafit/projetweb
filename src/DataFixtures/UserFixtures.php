<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $userE = new User();
        $userP = new User();

        $userE->setPrenom('userE')
            ->setNom('userE')
            ->setEmail('userE@user.com')
            ->setUsername('UserE')
            ->setPassword('test')
            ->setProf(false);

        $userP->setPrenom('userP')
            ->setNom('userP')
            ->setEmail('userP@user.com')
            ->setUsername('userP')
            ->setPassword('test')
            ->setProf(true);

        $manager->persist($userE);
        $manager->persist($userP);

        $manager->flush();
    }
}
