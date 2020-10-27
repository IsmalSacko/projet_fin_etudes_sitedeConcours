<?php

namespace App\Entity;

use App\Repository\AnnoncesRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Notre classe principale qui gère toutes les annonces mises en ligne.
 * @ORM\Entity(repositoryClass=AnnoncesRepository::class)
 * @ORM\HasLifecycleCallbacks()
 *
 */
class Annonces
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=6, max=255, minMessage="Le titre doit faire 6 caractères minimum",
     *maxMessage="Le titre ne doit pas dépasser 255 caractères !")
     */
    private $title;

    /**
     * Le slug ici permet de transformer le titre d'une annonce en slug et c'est automatique.
     * @ORM\Column(type="string", length=255)
     */
    private $slug;



    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=10, minMessage="Le contenu doit faire 10 caractère minimum")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="annonces", orphanRemoval=true, cascade={"persist"})
     */
    private $images;

    /**
     * Cette propriété permet de savoir qui est l'auteur d'une annonce publiée sur le site.
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * IL s'agit du département dans lequel une annonce se déroule ou va dérouler
     * @ORM\ManyToOne(targetEntity=Departement::class, inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $departement;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * Permet de choisir un type de concours parmi une liste déjà disponible.
     * @ORM\ManyToOne(targetEntity=TypeConcours::class, inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $typeConcours;

    /**
     * @ORM\ManyToOne(targetEntity=Regions::class, inversedBy="annonces")
     */
    private $region;

    /**
     * Constructeur de notre classe par défaut.
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();

    }

    /**
     * Permet d'initialiser le slug avant la persistence
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function initializeSlug(){
        if (empty($this->slug)){
            $slugify = new Slugify();
        $this->slug = $slugify->slugify($this->title);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }


    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }



    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAnnonces($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getAnnonces() === $this) {
                $image->setAnnonces(null);
            }
        }

        return $this;
    }


    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }


public function __toString()
{
    return (string)$this->getSlug();
}

public function getDepartement(): ?Departement
{
    return $this->departement;
}

public function setDepartement(?Departement $departement): self
{
    $this->departement = $departement;

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

public function getTypeConcours(): ?TypeConcours
{
    return $this->typeConcours;
}

public function setTypeConcours(?TypeConcours $typeConcours): self
{
    $this->typeConcours = $typeConcours;

    return $this;
}

public function getRegion(): ?Regions
{
    return $this->region;
}

public function setRegion(?Regions $region): self
{
    $this->region = $region;

    return $this;
}

}
