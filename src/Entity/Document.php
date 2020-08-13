<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DocumentRepository")
 */
class Document
{
    const LISTE_EXTENSIONS = [
        'png'  => 'image/png',
        'jpeg' => 'image/jpeg',
        'jpg'  => 'image/jpeg',
        'gif'  => 'image/gif'
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Page", inversedBy="documents")
     */
    private $page;

    /**
     * @var UploadedFile
     */
    private $fichier;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $originalDocument;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $miniature;

    public function getId()
    {
        return $this->id;
    }

    public function getTitre()
    {
        return $this->titre;
    }

    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function setPage(Page $page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return UploadedFile
     */
    public function getFichier()
    {
        return $this->fichier;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $fichier
     */
    public function setFichier($fichier)
    {
        $this->fichier = $fichier;
    }

    public function getOriginalDocument()
    {
        return $this->originalDocument;
    }

    public function setOriginalDocument($originalDocument)
    {
        $this->originalDocument = $originalDocument;

        return $this;
    }

    public function getMiniature(): ?bool
    {
        return $this->miniature;
    }

    public function setMiniature(?bool $miniature): self
    {
        $this->miniature = $miniature;

        return $this;
    }

}
