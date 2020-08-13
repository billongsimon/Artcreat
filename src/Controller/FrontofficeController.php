<?php

namespace App\Controller;

use App\Entity\Page;
use App\Repository\PageRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Renderer\CKEditorRendererInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FrontofficeController extends AbstractController
{
    /**
     * @Route("/", name="frontoffice")
     * @param EntityManagerInterface $entityManagerInterface
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(EntityManagerInterface $entityManagerInterface)
    {
        /** @var PageRepository $repoPage */
        $repoPage = $entityManagerInterface->getRepository(Page::class);

        $aLaUne = $repoPage->findPagesALaUne();
        $vieDesProjets = $repoPage->findPagesVieDesProjets();
        $agenda = $repoPage->findPagesAgenda();

        return $this->render('frontoffice/index.html.twig', [
            'aLaUne'        => $aLaUne,
            'vieDesProjets' => $vieDesProjets,
            'agenda'        => $agenda
        ]);
    }

    /**
     * @Route("/page/{id}")
     * @param EntityManagerInterface $entityManagerInterface
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function page(Page $page)
    {
//        /** @var PageRepository $repoPage */
//        $repoPage = $entityManagerInterface->getRepository(Page::class);
//
//        $aLaUne = $repoPage->findPagesALaUne();
//        $vieDesProjets = $repoPage->findPagesVieDesProjets();
//        $agenda = $repoPage->findPagesAgenda();

        return $this->render('frontoffice/page.html.twig', [
            'page' => $page
        ]);
    }
}
