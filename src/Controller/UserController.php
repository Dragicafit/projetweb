<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/user", name="create_user")
     */
    public function create_user()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setEmail('tom.payet1gmail.com');
        $user->setNom('Payet');
        $user->setPrenom('Tom');
        $user->setPseudo('Flaye');
        $user->setPassword('testuser');
        $user->setTypeProf(true);

        // tell Doctrine you want to (eventually) save the user (no queries yet)
        $entityManager->persist($user);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new user with id '.$user->getId());
    }
}
