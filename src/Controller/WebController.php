<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Cours;

class WebController extends AbstractController
{
    /**
     * @Route("/web", name="web")
     */
    public function index()
    {
        return $this->render('web/index.html.twig', [
            'controller_name' => 'WebController',
        ]);
    }

      

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        $repo = $this->getDoctrine()->getRepository(Cours::class);
        
        $cours = $repo->findAll();

        return $this->render('web/home.html.twig', ['connect' => false,'prof'=>true, 'liste_cours'=>$cours]);
    }

    /**
     * @Route("/cours/{id}", name="cour_view")
     */
    public function show($id)
    {
        $repo = $this->getDoctrine()->getRepository(Cours::class);

        $cour = $repo->find($id);
        $val = $cour->getValue();
        shuffle($val);
        return $this->render('web/cour.html.twig', ['cour'=>$cour, 'val'=>$val]);
    }
}
