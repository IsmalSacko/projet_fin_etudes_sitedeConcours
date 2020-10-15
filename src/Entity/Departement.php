<?php

namespace App\Entity;

use App\Repository\DepartementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function Symfony\Component\String\s;

/**
 * Notre classe principale 
 * @ORM\Entity(repositoryClass=DepartementRepository::class)
 */
class Departement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $departement_nom_uppercase;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $departement_slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $departement_nom_soundex;

    /**
     * @ORM\OneToMany(targetEntity=Annonces::class, mappedBy="departement")
     */
    private $annonces;

    public function __construct()
    {
        $this->villes = new ArrayCollection();
        $this->annonces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDepartementNomUppercase(): ?string
    {
        return $this->departement_nom_uppercase;
    }

    public function setDepartementNomUppercase(string $departement_nom_uppercase): self
    {
        $this->departement_nom_uppercase = $departement_nom_uppercase;

        return $this;
    }

    public function getDepartementSlug(): ?string
    {
        return $this->departement_slug;
    }

    public function setDepartementSlug(string $departement_slug): self
    {
        $this->departement_slug = $departement_slug;

        return $this;
    }

    public function getDepartementNomSoundex(): ?string
    {
        return $this->departement_nom_soundex;
    }

    public function setDepartementNomSoundex(string $departement_nom_soundex): self
    {
        $this->departement_nom_soundex = $departement_nom_soundex;

        return $this;
    }

    public function getDepartementCode(): ?string
    {
        return $this->departement_code;
    }

    public function setDepartementCode(string $departement_code): self
    {
        $this->departement_code = $departement_code;

        return $this;
    }

    public function getDepartementNom(): ?string
    {
        return $this->departement_nom;
    }

    public function setDepartementNom(string $departement_nom): self
    {
        $this->departement_nom = $departement_nom;

        return $this;
    }
    public function __toString()
    {
        return (string)$this->departement_nom_uppercase;
    }

    /**
     * @return Collection|Annonces[]
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonces $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces[] = $annonce;
            $annonce->setDepartement($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonces $annonce): self
    {
        if ($this->annonces->contains($annonce)) {
            $this->annonces->removeElement($annonce);
            // set the owning side to null (unless already changed)
            if ($annonce->getDepartement() === $this) {
                $annonce->setDepartement(null);
            }
        }

        return $this;
    }

}
