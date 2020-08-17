<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $auteur;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $date;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contenu;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="Articles")
     */
    private $categorie;

    /**
    * @var Article
    * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="enfants")
     */
    private $parent;

    /**
    * var ArrayCollection
    * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="parent")
     */
    private $enfants;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Document", mappedBy="Article")
     */
    private $documents;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $visible;

    public function __construct()
    {
        $this->enfants = new ArrayCollection();
        $this->documents = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
    /**
     * @return Article $parent
     */

        public function getParent(): ?Article
        {
            return $this->parent;
        }
    /**
    * @param Article $parent
    */

        public function setParent( $parent): self
        {
            $this->parent = $parent;

            return $this;
        }

        /**
         * @return Collection|self[]
         */
        public function getEnfants(): Collection
        {
            // NOTE : c'est un critère de recherche pour trié par défaut les Articles enfants par titre
            $orderBy = (Criteria::create())->orderBy(['titre' => Criteria::ASC]);

            return $this->enfants->matching($orderBy);
        }

        public function addEnfant(self $enfant): self
        {
            if (!$this->enfants->contains($enfant)) {
                $this->enfants[] = $enfant;
            }

            return $this;
        }

        public function removeEnfant(self $enfant): self
    {
        if ($this->enfants->contains($enfant)) {
            $this->enfants->removeElement($enfant);
        }

            return $this;
    }

        /**
         * @return Collection|Document[]
         */
        public function getDocuments(): Collection
        {
            return $this->documents;
        }

        public function addDocument(Document $document): self
        {
            if (!$this->documents->contains($document)) {
                $this->documents[] = $document;
                $document->setArticle($this);
            }

            return $this;
        }

        public function removeDocument(Document $document): self
        {
            if ($this->documents->contains($document)) {
                $this->documents->removeElement($document);
                // set the owning side to null (unless already changed)
                if ($document->getArticle() === $this) {
                    $document->setArticle(null);
                }
            }

            return $this;
        }

        public function getVisible(): ?bool
        {
            return $this->visible;
        }

        public function setVisible(?bool $visible): self
        {
            $this->visible = $visible;

            return $this;
        }
}
