<?php

namespace App\Controller;

use App\Entity\Document;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Mime\MimeTypesInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/document")
 */
class DocumentController extends AbstractController
{
    /**
     * @Route("/", name="document_index", methods={"GET"})
     */
    public function index(DocumentRepository $documentRepository): Response
    {
        return $this->render('admin/document/index.html.twig', [
            'documents' => $documentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="document_new", methods={"GET","POST"})
     */
    public function newAction(Request $request): Response
    {
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            /** @var Document $document */
            $document = $form->getData();

            /** @var UploadedFile $file */
            $file = $document->getFichier();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $originalName = $file->getClientOriginalName();
            try {
                $file->move('../uploads', $fileName);
            } catch (FileException $e) {
                // ... gérer l'exception si quelque chose se produit pendant le téléchargement du fichier
            }

            $document->setOriginalDocument($fileName);
            $document->setTitre($originalName);
            $entityManager->persist($document);
            $entityManager->flush();
            $this->addFlash('success', "Votre fichier a été uploadé ");
            return $this->redirectToRoute('document_index');

            return $this->redirectToRoute('document_index');
        }

        return $this->render('admin/document/new.html.twig', [
            'document' => $document,
            'form'     => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="document_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Document $document): Response
    {
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('document_index');
        }

        return $this->render('admin/document/edit.html.twig', [
            'document' => $document,
            'form'     => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="document_delete", methods={"GET"})
     * @param Request $request
     * @param Document $document
     * @param EntityManagerInterface $entityManagerInterface
     * @param Filesystem $filesystem
     * @param $uploadDir
     * @return Response
     */
    public function delete(Request $request, Document $document, EntityManagerInterface $entityManagerInterface, Filesystem $filesystem, $uploadDir): Response
    {
        try {
            $entityManagerInterface->remove($document);
            $entityManagerInterface->flush();

            $filesystem->remove($uploadDir . $document->getOriginalDocument());
        } catch (\Exception $e) {
            throw new \DomainException($e->getMessage());
        }
        return $this->redirectToRoute('document_index');
    }

    /**
     * @Route("/admin/document/download/{originalDocument}", methods={"GET"})
     * @return BinaryFileResponse
     */
    public function downloadAction($uploadDir, Document $document)
    {
        try {
            $file = $uploadDir . $document->getOriginalDocument(); // Path to the file on the server
            return $this->file($file, $document->getTitre());
        } catch (\Exception $e) {
            throw new \DomainException($e->getMessage());
        }
    }

    /**
     * @Route("/admin/document/load/{originalDocument}", methods={"GET"})
     * @param Request $request
     * @param $uploadDir
     * @param Document $document
     * @return BinaryFileResponse
     */
    public function loadAction(Request $request, $uploadDir, Document $document)
    {
        $filepath = $uploadDir . $document->getOriginalDocument();
        $filename = $document->getOriginalDocument();

        /** @var UploadedFile $file */
        $file = $this->file($filepath, $document->getOriginalDocument())->getFile();

        $extension = $file->guessExtension();

        $mimeTypes = new MimeTypes();
        $mimeType = $mimeTypes->getMimeTypes($extension);

        $response = new Response();
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $filename);
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Type', $mimeType[0]);
        $response->setContent(file_get_contents($filepath));
        return $response;
    }
}

