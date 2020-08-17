<?php

namespace App\Controller;

use App\BusinessService\ArborescenceBS;
use App\Entity\Categorie;
use App\Entity\Document;
use App\Entity\Article;
use App\Form\ArticleType;
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
     * @Route("/Article/index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    // Ma Article d'accueil
    public function Article_index(request $request, ArborescenceBS $arborescenceBS)
    {
        // Récupération de l'arborescences des Articles
        $arborescences = $arborescenceBS->arbre();

        // NOTE : Nous aurrions pu récupérer simplement la liste des Articles par le répository.
        // Mais nous n'aurions pas récupéré le notion d'arbre. C'est à dire que les Articles enfants suivent la Article parent,
        // même si elle ont étés créées bien après une Article de niveau supérieure

        return $this->render('admin/Article/index.html.twig', [
            'arbre' => $arborescences
        ]);

    }

    /**
     * @Route("/Article/new")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    // formulaire de création de la Article
    public function Article_new(Request $request, EntityManagerInterface $manager)
    {
        date_default_timezone_set('Europe/Paris');
        $form = $this->createForm(ArticleType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $Article = $form->getData();
            $Article->setCreatedAt(new \DateTime());
            //  $Article->setJourAt(new \DateTime());
            $manager->persist($Article);
            $manager->flush();

            return $this->redirectToRoute('app_admin_Article_index',
                ['id' => $Article->getId()]); // Redirection vers la Article
        }
        return $this->render('admin/Article/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/Article/{id}")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param Article $Article
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    // formulaire de modification de la Article
    public function Article_edit(Article $Article, Request $request, EntityManagerInterface $manager)
    {
        // $Article->setJourAt(new \DateTime());

        // NOTE : ici inutile de créer un formulaire différent de "new". On réutilise le formulaire précédent.

        $form = $this->createForm(ArticleType::class, $Article);
        $form->handleRequest($request);

        if ($form->isSubMitted() && $form->isValid()) {
            $manager->persist($Article);
            //    $Article->setJourAt(new \DateTime());
            $manager->persist($Article);
            $manager->flush();

            return $this->redirectToRoute('app_admin_Article_index');
        }
        return $this->render('admin/Article/edit.html.twig', [
            'form' => $form->createView(),
            'Article' => $Article
        ]);
    }

    /**
     * @Route("/Article/{id}/delete")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    // suppression de la Article
    public function Article_delete($id, EntityManagerInterface $manager, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Article::class);
        $Article = $repo->find($id);


        $manager->remove($Article);
        $manager->flush();

        return $this->redirectToRoute('app_admin_Article_index');
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
