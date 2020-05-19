<?php

namespace App\Controller;

use App\Entity\User;
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
    public function home(EntityManagerInterface $manager)
    {
        $repo = $manager->getRepository(Cours::class);
        
        $cours = $repo->findAll();

        return $this->render('web/home.html.twig', ['liste_cours'=>$cours]);
    }

    public function newExo($request, EntityManagerInterface $manager, $cour, $exercice, $form)
    {
        $nb_solution = $request->get('count_sol');
        $value = $request->get('solution1');
        $consigne = $request->get('consigne');
        $cour->addExercice($exercice);
        $exercice->initExercice($value, $consigne, $manager);
            
        for ($solu = 2; $solu<=$nb_solution; $solu++) {
            $exercice->parseSolution($request->get('solution'.$solu), $manager);
        }
        $manager->flush();
        if ($form->get('save')->isClicked()) {
            return $this->redirectToRoute('home');
        }
        return $this->redirectToRoute('add_exo', ['id' => $cour->getId()]);
    }

    /**
     * @Route("/cour/create", name="createCour")
     */
    public function create_cour(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
        $cour = new Cours();
        $exercice = new Exercice();

        $form = $this->createFormBuilder($cour)
                    ->add(
                        'save',
                        SubmitType::class,
                        ['label'=>'Enregistrer', 'attr'=>[
                            'class'=>'btn btn-primary'
                        ]
                    ]
                    )
                    ->add('next', SubmitType::class, ['label'=>'Ajouter un exercice', 'attr'=>['class'=>'btn btn-primary']])
                    ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cour->setTitle($request->request->get('titre'))
                ->setAuteur($user)
                ->setTemps($request->request->get('temps'));
            $manager->persist($cour);
            $manager->flush();

            return $this->newExo($request->request, $manager, $cour, $exercice, $form);
        }
        return $this->render('web/createCour.html.twig', ['formCour'=>$form->createView()]);
    }

    /**
     * @Route("/cours/{id}", name="cour_view")
     */
    public function show($id)
    {
        return $this->redirectToRoute('cour_exo', ['id' => $id, 'exo_id' => 0]);
    }

    /**
     * @Route("cours/{id}/exo/{exo_id}", name="cour_exo")
     */
    public function show_next($id, $exo_id, UserInterface $user, EntityManagerInterface $manager)
    {
        $repo = $manager->getRepository(Cours::class);

        $cour = $repo->find($id);
        $exo = $cour->getExercices();
        if ($exo_id==0) {
            $user->addCoursEleve($cour);
        }

        if ($exo_id >= sizeof($exo)) {
            $user->addDone($cour);
            $manager->flush();
            return $this->render('web/finexo.html.twig');
        }
        $manager->flush();
        $val = $exo[$exo_id]->getLigne();
        $cons = $exo[$exo_id]->getConsigne();
        return $this->render('web/cour.html.twig', ['cour'=>$cour, 'exo'=>$val, 'c_id'=> $id, 'e_id'=> $exo_id, 'cons'=>$cons]);
    }

    /**
     * @Route("/mescours", name="mes_cours")
     */
    public function my_cours(UserInterface $user, EntityManagerInterface $manager)
    {
        $cours = $user->getCoursProf();
        return $this->render('web/home.html.twig', ['liste_cours'=>$cours]);
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
            $repo = $manager->getRepository(Cours::class);
            $cour = $repo->find($id);
            return $this->newExo($request->request, $manager, $cour, $exercice, $form);
        }
        return $this->render('web/newExercice.html.twig', ['formExo'=>$form->createView()]);
    }
    
    /**
     * @Route("/exo_list/{id}", name="exo_cours")
     */
    public function liste_exos($id, EntityManagerInterface $manager)
    {
        $repo_cour = $manager->getRepository(Cours::class);
        $cour = $repo_cour->find($id);
        $exos = $cour->getExercices();
        return $this->render('web/pageExo.html.twig', ['exos'=>$exos]);
    }
}
