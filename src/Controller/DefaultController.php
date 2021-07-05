<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController {
    /**
     * @Route ("/", name="home")
     */
    public function home() {
        return $this-> render("default/home.html.twig");
        //bucket-list\templates
    }

    /**
     * @Route("/about-us", name="about")
     */
    public function about()
    {
        return $this->render('default/about.html.twig');
    }

}

