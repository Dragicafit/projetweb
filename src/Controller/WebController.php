<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Cours;
use App\Entity\ExoUser;
use App\Entity\Exercice;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $bad_l = $request->get('bad_lines');
        $exercice->parseLigne($bad_l, $manager);
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
        if (!$user->getProf()) {
            throw $this->createNotFoundException('Vous ne devriez pas être ici !');
        }
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
     * @Route("/cours/{cour_id}", name="cour_view")
     */
    public function show($cour_id)
    {
        return $this->redirectToRoute('cour_exo', ['cour_id' => $cour_id, 'cour_exo_id' => 0]);
    }


    public function exo_precedent($cour_exo_id, $exos, UserInterface $user)
    {
        $exo_p = $cour_exo_id -1;
        if ($exo_p < 0) {
            throw $this->createNotFoundException('Introuvable');
        }
        $exo_pre = $exos[$exo_p];
        $exo_users = $exo_pre -> getExoUsers();
        foreach ($exo_users as $exo_user) {
            if ($exo_user->getEleve() == $user && ($exo_user->getWin() || $exo_user->getAbandon())) {
                return true;
            }
        }
        return false;
    }

    /**
    * @Route("/abandon/exo/{exo_id}", name="abandon")
    */
    public function abandon($exo_id, Request $request, UserInterface $user, EntityManagerInterface $manager)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException('The product does not exist');
        }

        $repo = $manager->getRepository(Exercice::class);
        $exercice = $repo->find($exo_id);
        if ($exercice === null) {
            throw $this->createNotFoundException('The product does not exist');
        }
        $exo_user = ExoUser::initExoUser($user, $exercice, false, $manager);
        $exo_user ->setAbandon(true);
        $manager->flush();
        return new JsonResponse();
    }

    /**
     * @Route("cours/{cour_id}/exo/{cour_exo_id}", name="cour_exo")
     */
    public function show_next($cour_id, $cour_exo_id, Request $request, UserInterface $user, EntityManagerInterface $manager)
    {
        if ($cour_id < 0 || $cour_exo_id < 0) {
            throw $this->createNotFoundException('Introuvable');
        }

        $repo = $manager->getRepository(Cours::class);
        $cour = $repo->find($cour_id);
        
        if ($cour === null) {
            throw $this->createNotFoundException('Introuvable');
        }
        
        $exos = $cour->getExercices();
        $cour->addEleve($user);
        if ($cour_exo_id==0) {
            $user->addCoursEleve($cour);
        } else {
            if (!$this->exo_precedent($cour_exo_id, $exos, $user)) {
                throw $this->createNotFoundException('Introuvable');
            }
        }

        if ($cour_exo_id >= sizeof($exos)) {
            /* Gerer la reussite de l'exo */
            $manager->flush();
            return $this->render('web/finexo.html.twig');
        }

        $exo =$exos[$cour_exo_id];
        $lignes = $exo->getLigne();
        $solutions = $exo->getSolution();
        $lignes_solutions = [];
        foreach ($lignes as $ligne) {
            $count = 0;
            foreach ($solutions as $solution) {
                $count_on_solution = 0;
                foreach ($solution->getTab() as $tab) {
                    if ($tab->getLigne() == $ligne) {
                        $count_on_solution ++;
                    }
                }
                $count = max($count, $count_on_solution);
            }
            for ($i = 0; $i<max(1, $count);$i++) {
                $lignes_solutions[]=$ligne;
            }
        }
        $cons = $exo->getConsigne();

        $exo_id = $exo->getId();
        shuffle($lignes_solutions);
        $manager->flush();

        return $this->render('web/cour.html.twig', ['cour'=>$cour, 'exo'=>$lignes_solutions, 'cour_id'=> $cour_id, 'cour_exo_id'=> $cour_exo_id, 'exo_id'=> $exo_id, 'cons'=>$cons]);
    }

    /**
     * @Route("/verif/exo/{exo_id}", name="verif")
     */
    public function verif($exo_id, Request $request, UserInterface $user, EntityManagerInterface $manager)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException('The product does not exist');
        }

        $repo = $manager->getRepository(Exercice::class);
        $exercice = $repo->find($exo_id);
        if ($exercice === null) {
            throw $this->createNotFoundException('The product does not exist');
        }
        $solutions = $exercice->getSolution();

        if ($solutions === null) {
            throw $this->createNotFoundException('The product does not exist');
        }

        $rep=$request->request->get('rep', []);

        foreach ($solutions as $solution) {
            $tab = $solution->getTab();
            if (sizeof($tab) == sizeof($rep)) {
                for ($i = 0; $i < sizeof($tab); $i++) {
                    if ($tab[$i]->getNbTab() != $rep[$i]['nb_tab'] || $tab[$i]->getLigne()->getId() != $rep[$i]['ligne_id']) {
                        continue 2;
                    }
                }
                $exo_user = ExoUser::initExoUser($user, $exercice, true, $manager);
                $manager->flush();
                return new JsonResponse(true);
            }
        }
        $exo_user = ExoUser::initExoUser($user, $exercice, false, $manager);
        $manager->flush();
        return new JsonResponse(false);
    }

    /**
     * @Route("/mescours", name="mes_cours")
     */
    public function my_cours(UserInterface $user, EntityManagerInterface $manager)
    {
        if (!$user->getProf()) {
            $cours = $user->getCoursEleve();
            return $this->render('web/myCours.html.twig', ['liste_cours'=>$cours]);
        }
        $cours = $user->getCoursProf();
        return $this->render('web/home.html.twig', ['liste_cours'=>$cours]);
    }

    /**
     * @Route("/newExo/{id}", name="add_exo")
     */
    public function add_exo(UserInterface $user, Request $request, EntityManagerInterface $manager, $id)
    {
        if (!$user->getProf()) {
            throw $this->createNotFoundException('Vous ne devriez pas être ici !');
        }
        $c = $manager->getRepository(Cours::class)->find($id);
        if (is_null($c)) {
            throw $this->createNotFoundException('Vous voulez aller trop vite !');
        }
        if ($c->getAuteur() != $user) {
            throw $this->createNotFoundException('Vous ne devriez pas être ici !');
        }

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
     * @Route("/result/user", name="user_result")
     */
    public function user_result(EntityManagerInterface $manager, Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException('The request does not exist');
        }
        $type = $request->request->get('type');
        $repo = $manager->getRepository(User::class);
        $cour_id = $request->request->get('cour_id');
        $user_id = $request->request->get('user_id');
        $user = $repo->find($user_id);
        if ($user === null) {
            throw $this->createNotFoundException('The request does not exist');
        }
        $user_exo = $user->getEleveExo();
        if ($user_exo === null) {
            throw $this->createNotFoundException('The request does not exist');
        }
        if ($type == -1) {
            $note = 0;
            foreach ($user_exo as $eleve_exo) {
                if ($eleve_exo->getExercice()->getCour()->getId() == $cour_id) {
                    /* Voir comment on calcul le taux de réussite */
                    $note+= $eleve_exo->getNbErreur();
                }
            }
            return new JsonResponse($note);
        }
        $exo_id = $request->request->get('exo_id');
        foreach ($user_exo as $eleve_exo) {
            if ($eleve_exo->getExercice()->getId() == $exo_id) {
                /* A voir ce qu'on affiche */
                return new JsonResponse($eleve_exo->getNbErreur());
            }
        }
        return new JsonResponse("---");
    }
    
    /**
     * @Route("/exo_list/{id}", name="exo_cours")
     */
    public function liste_exos($id, EntityManagerInterface $manager)
    {
        $repo_cour = $manager->getRepository(Cours::class);
        $cour = $repo_cour->find($id);
        $exos = $cour->getExercices();
        return $this->render('web/pageExo.html.twig', ['exos'=>$exos, 'cour_id' => $id]);
    }
}
