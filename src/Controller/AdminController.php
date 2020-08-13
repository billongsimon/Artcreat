<?php

namespace App\Controller;

use App\BusinessService\ArborescenceBS;
use App\Entity\Categorie;
use App\Entity\Document;
use App\Entity\Page;
use App\Form\PageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_default")
     * @Route("/page/index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    // Ma page d'accueil
    public function page_index(request $request, ArborescenceBS $arborescenceBS)
    {
        // Récupération de l'arborescences des pages
        $arborescences = $arborescenceBS->arbre();

        // NOTE : Nous aurrions pu récupérer simplement la liste des pages par le répository.
        // Mais nous n'aurions pas récupéré le notion d'arbre. C'est à dire que les pages enfants suivent la page parent,
        // même si elle ont étés créées bien après une page de niveau supérieure

        return $this->render('admin/page/index.html.twig', [
            'arbre' => $arborescences
        ]);

    }

    /**
     * @Route("/page/new")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    // formulaire de création de la page
    public function page_new(Request $request, EntityManagerInterface $manager)
    {
        date_default_timezone_set('Europe/Paris');
        $form = $this->createForm(PageType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $page = $form->getData();
            $page->setCreatedAt(new \DateTime());
            //  $page->setJourAt(new \DateTime());
            $manager->persist($page);
            $manager->flush();

            return $this->redirectToRoute('app_admin_page_index',
                ['id' => $page->getId()]); // Redirection vers la page
        }
        return $this->render('admin/page/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/page/{id}")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param Page $page
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    // formulaire de modification de la page
    public function page_edit(page $page, Request $request, EntityManagerInterface $manager)
    {
        // $page->setJourAt(new \DateTime());

        // NOTE : ici inutile de créer un formulaire différent de "new". On réutilise le formulaire précédent.

        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubMitted() && $form->isValid()) {
            $manager->persist($page);
            //    $page->setJourAt(new \DateTime());
            $manager->persist($page);
            $manager->flush();

            return $this->redirectToRoute('app_admin_page_index');
        }
        return $this->render('admin/page/edit.html.twig', [
            'form' => $form->createView(),
            'page' => $page
        ]);
    }

    /**
     * @Route("/page/{id}/delete")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    // suppression de la page
    public function page_delete($id, EntityManagerInterface $manager, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Page::class);
        $page = $repo->find($id);


        $manager->remove($page);
        $manager->flush();

        return $this->redirectToRoute('app_admin_page_index');
    }

    /**
     * @Route("/categorie")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    // liste des catégories
    public function categorie_index(Request $request)
    {
        $repos = $this->getDoctrine()->getRepository(Categorie::class);
        $categories = $repos->findAll();

        return $this->render('admin/categorie/index.html.twig', [
            'controller_name' => 'AdminController',
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/categorie/new")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    // formulaire de création d'une catégorie
    public function categorie_new(Request $request, EntityManagerInterface $manager)
    {
        $categorie = new Categorie();
        $form = $this->createFormBuilder($categorie)
            ->add('titre')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($categorie);
            $manager->flush();
            return $this->redirectToRoute('app_admin_categorie_index'); // Redirection vers
        }
        return $this->render('admin/categorie/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/categorie/{id}")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param Categorie $categorie
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    // formulaire de modification d'une catégorie
    public function categorie_edit(categorie $categorie, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createFormBuilder($categorie)
            ->add('titre')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubMitted() && $form->isValid()) {
            $manager->persist($categorie);
            $manager->flush();

            return $this->redirectToRoute('app_admin_categorie_index');
        }
        return $this->render('admin/categorie/edit.html.twig', [
            'form' => $form->createView(),
            'categorie' => $categorie
        ]);
    }

    /**
     * @Route("/categorie/{id}/delete")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    // suppression de la catégorie
    public function categorie_delete($id, EntityManagerInterface $manager, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Categorie::class);
        $categorie = $repo->find($id);

        $manager->remove($categorie);
        $manager->flush();

        return $this->redirectToRoute('app_admin_categorie_index');
    }
}
