<?php
//src/Controller/inscription.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class inscription extends AbstractController
{
    /*
    * @Route("/inscription")
    */
    public function start()
    {
        return $this->render("Inscription/Form.html.twig");
    }

    /*
    * @Route("/connexion")
    */
    public function connect()
    {
        return $this->render("connexion.html.twig");
    }
}
