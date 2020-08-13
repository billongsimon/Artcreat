<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Livres;

class LivreController extends AbstractController
{
    /**
     * @Route("/livre", name="livre")
     */
    public function index()
    {
        
        $repo = $this->getDoctrine()->getRepository(Livres::class);
        $livres = $repo ->findAll();

        return $this->render('livre/index.html.twig', [
            'controller_name' => 'LivreController',
            'livres'=>$livres
        ]);
    }
}
