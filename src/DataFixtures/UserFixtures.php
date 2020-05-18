<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $userP = new User();
        $userP->setPrenom("Prof")
        ->setNom("Prof")
        ->setEmail("prof@prof.com")
        ->setUsername("UserP")
        ->setPassword($this->encoder->encodePassword($userP, 'testtest'))
        ->setProf(true);

        $manager->persist($userP);

        $userE = new User();
        $userE->setPrenom("Eleve")
        ->setNom("Eleve")
        ->setEmail("eleve@eleve.com")
        ->setUsername("UserE")
        ->setPassword($this->encoder->encodePassword($userE, 'testtest'))
        ->setProf(false);

        $manager->persist($userE);

        $manager->flush();
    }
}
