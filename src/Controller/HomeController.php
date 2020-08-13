<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     * @Route("/", name="accueil")
     */
    public function home()
    {
        return $this->render('home/blog.html.twig', [
            'controller_name' => 'HomeController',
            'title'=>"BIENVENUE SUR NOTRE SITE",
            'heure'=>"Il est : ",
        ]);
    }

    /** 
     * @Route("/", name="crp")
    */
/*    public function index()
    {
        return $this ->render('home/blog.html.twig',[
            'title'=>"BIENVENUE SUR NOTRE SITE",
            'heure'=>"Il est : ",
        ]); 
   } */

}
