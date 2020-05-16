<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Exercice;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType ;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WebController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        $repo = $this->getDoctrine()->getRepository(Cours::class);
        
        $cours = $repo->findAll();

        return $this->render('web/home.html.twig', ['liste_cours'=>$cours]);
    }

    /**
     * @Route("/cour/create", name="createCour")
     */
    public function create_cour(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
        $cour = new Cours();
        $exo = new Exercice();

        $form = $this->createFormBuilder($cour)
                    ->add(
                        'save',
                        SubmitType::class,
                        ['label'=>'Enregistrer', 'attr'=>[
                            'class'=>'btn btn-primary'
                        ]
                    ]
                    )
                    ->add('next', SubmitType::class, ['label'=>'Ajouter un exercice', 'attr'=>['class'=>'btn btn-primary pull-right']])
                    ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $value = explode("\n", str_replace("\r", "", $request->request->get('value')));
            
            $cour->setTitle($request->request->get('titre'))
                ->setAuteur($user->getUsername())
                ->setTemps($request->request->get('temps'));
            
            $manager->persist($cour);
            $manager->flush();

            $exo->setExo($value)
                ->setConsigne($request->request->get('consigne'));
            $manager->persist($exo);
            $cour->addExercice($exo);
            $manager->persist($exo);
            $manager->flush();

            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('home');
            } else {
                return $this->redirectToRoute('add_exo', ['id' => $cour->getId()]);
                //return $this->render('web/newExercice.html.twig', ['userid'=>$cour->getId()]);
            }
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
        $exo = $cour->getExercices();
        $val = $exo[0]->getExo();
        $res = $val;
        $cons = $exo[0]->getConsigne();
        shuffle($val);
        return $this->render('web/cour.html.twig', ['cour'=>$cour, 'exo'=>$val, 'res'=>$res, 'c_id'=> $id, 'e_id'=> 0, 'cons'=>$cons]);
    }

    /**
     * @Route("cours/{id}/exo/{exo_id}", name="cour_exo")
     */
    public function show_next($id, $exo_id, UserInterface $user, EntityManagerInterface $manager)
    {
        $repo = $this->getDoctrine()->getRepository(Cours::class);

        $cour = $repo->find($id);
        $exo = $cour->getExercices();
        if ($exo_id >= sizeof($exo)) {
            $user->addDone($cour);
            $manager->persist($cour);
            $manager->flush();
            return $this->render('web/finexo.html.twig');
        }
        $val = $exo[$exo_id]->getExo();
        $res = $exo[$exo_id]->getExo();
        $cons = $exo[$exo_id]->getConsigne();
        shuffle($val);
        return $this->render('web/cour.html.twig', ['cour'=>$cour, 'exo'=>$val, 'res'=>$res, 'c_id'=> $id, 'e_id'=> $exo_id, 'cons'=>$cons]);
    }

    /**
     * @Route("/mescours", name="mes_cours")
     */
    public function my_cours(UserInterface $user)
    {
        $repo = $this->getDoctrine()->getRepository(Cours::class);

        $cour = $repo->findBy(array('auteur' => $user->getUsername()));
        return $this->render('web/home.html.twig', ['liste_cours'=>$cour]);
    }

    /**
     * @Route("/newExo/{id}", name="add_exo")
     */
    public function add_exo(Request $request, EntityManagerInterface $manager, $id)
    {
        $exercice = new Exercice();

        $form = $this->createFormBuilder($exercice)
                    ->add('valId', HiddenType::class, [
                        'mapped'=>false                        ])
                    ->add(
                        'save',
                        SubmitType::class,
                        ['label'=>'Enregistrer', 'attr'=>[
                            'class'=>'btn btn-primary'
                        ]
                    ]
                    )
                    ->add('next', SubmitType::class, ['label'=>'Ajouter un exercice', 'attr'=>['class'=>'btn btn-primary pull-right']])
                    ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repo = $this->getDoctrine()->getRepository(Cours::class);

            $value = explode("\n", str_replace("\r", "", $request->request->get('value')));
            
            $exercice->setExo($value);
            $exercice->setConsigne($request->request->get('consigne'));

            $manager->persist($exercice);
            $cour = $repo->find($id);
            $cour->addExercice($exercice);
            $manager->persist($cour);
            $manager->flush();
            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('home');
            } else {
                return $this->redirectToRoute('add_exo', ['id' => $cour->getId()]);
                //return $this->render('web/newExercice.html.twig', ['userid'=>$cour->getId()]);
            }
        }
        return $this->render('web/newExercice.html.twig', ['formExo'=>$form->createView()]);
    }
}
