<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Cocur\Slugify\Slugify;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"email"}, message="Une autre utilisateur possède déjà cet email, veillez utiliser un autre.")
  */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="LE prenom ne peut pas être vide !")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom ne peut pas être vide !")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Il faut entrer une adresse email valide !")
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;
    /**
     * @Assert\EqualTo(propertyPath="hash", message="Les mots de passe ne sont pas identiques !")
     */
    public $confirmPassword;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=6, max=70, minMessage="Le titre doit faire 6 caractères minimum",
     *maxMessage="Le titre ne doit pas dépasser 70 caractères !")
     */
    private $intro;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=6, max=155, minMessage="Le titre doit faire 6 caractères minimum",
     *maxMessage="Le titre ne doit pas dépasser 155 caractères !")
     */
    private $presentation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTimeInterface|null
     */

    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity=Annonces::class, mappedBy="author", orphanRemoval=true)
     */
    private $annonces;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, mappedBy="users")
     */
    private $usersRoles;





    /**
     * Permet d'initialiser le slug avant la persistence
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function initializeSlug(){
        if (empty($this->slug)){
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->getFirstName());
        }
    }
    /**
     * @ORM\PrePersist()
     */
    public function updateDate(){

        if (empty($this->updated_at)){
            $this->updated_at = new \DateTime();
        }
    }

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
        $this->usersRoles = new ArrayCollection();
        $this->achats = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }


    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getIntro(): ?string
    {
        return $this->intro;
    }

    public function setIntro(string $intro): self
    {
        $this->intro = $intro;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

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
            $annonce->setAuthor($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonces $annonce): self
    {
        if ($this->annonces->contains($annonce)) {
            $this->annonces->removeElement($annonce);
            // set the owning side to null (unless already changed)
            if ($annonce->getAuthor() === $this) {
                $annonce->setAuthor(null);
            }
        }

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updated_at;
    }


    public function setUpdatedAt(DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param string $picture
     * @return User
     */
    public function setPicture(string $picture): User
    {
        $this->picture = $picture;
        return $this;
    }


    public function getRoles(){
        $roles = $this->usersRoles->map(function ($role){
            return $role->getTitle();
        })->toArray();
        $roles[]= 'ROLE_USER';
        return $roles;
    }

    public function getPassword(){
        return $this->hash;
    }

    public function getSalt(){}

    public function getUsername(){
        return $this->email;
    }

    public function eraseCredentials(){}

    public function __toString()
    {
        return (string)$this->getFullName();
    }

    /**
     * Permet de concaténer le firname et le lasname de l'utilisateur à fois
     * @return string
     */
    public function getFullName(){
        return "{$this->firstName} {$this->lastName}";
    }

    /**
     * @return Collection|Role[]
     */
    public function getUsersRoles(): Collection
    {
        return $this->usersRoles;
    }

    public function addUsersRole(Role $usersRole): self
    {
        if (!$this->usersRoles->contains($usersRole)) {
            $this->usersRoles[] = $usersRole;
            $usersRole->addUser($this);
        }

        return $this;
    }

    public function removeUsersRole(Role $usersRole): self
    {
        if ($this->usersRoles->contains($usersRole)) {
            $this->usersRoles->removeElement($usersRole);
            $usersRole->removeUser($this);
        }

        return $this;
    }


}
