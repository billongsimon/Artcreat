<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoursController extends AbstractController
{
    /**
     * @Route("/cours", name="home")
     * 
     */
    
    public function index(): Response
    {
        return $this->render('home/cours.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

}