<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType ;


use App\Entity\Cours;
use Doctrine\ORM\EntityManagerInterface;

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

        return $this->render('web/home.html.twig', ['connect' => false,'prof'=>false, 'liste_cours'=>$cours]);
    }

    /**
     * @Route("/cour/create", name="createCour")
     */
    public function create_cour(Request $request, EntityManagerInterface $manager)
    {
        $cour = new Cours();

        $form = $this->createFormBuilder($cour)
                    ->add('title', TextType::class, [
                            'attr'=>[
                                'placeholder'=>'Titre',
                                'class' => 'form-control'

                                ]
                            ])
                    ->add('temps', NumberType::class, [
                            'attr'=>[
                                'placeholder'=>'Temps',
                                'class' => 'form-control'
                                ]
                            ])
                    ->add(
                        'save',
                        SubmitType::class,
                        ['label'=>'Enregistrer', 'attr'=>[
                            'class'=>'btn btn-primary'
                        ]
                    ]
                    )
                    ->getForm();
        // $form->handleRequest($request);

        if ($request->request->count()>0) {
            $cour = new Cours();
            $value = explode("\n", str_replace("\r", "", $request->request->get('value')));
            $cour -> setAuteur('Jean-Bob')
                  ->setTitle($request->request->get('title'))
                  ->setTemps($request->request->get('temps'))
                  ->setValue($value);
            $manager->persist($cour);
            $manager->flush();

            return $this->redirectToRoute('home');
        }
        return $this->render('web/createCour.html.twig', ['formCour'=>$form->createView()]);
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
