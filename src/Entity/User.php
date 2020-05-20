<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="L'e-mail que vous avez indiqué est déjà utilisé !")
 * @UniqueEntity(fields="username", message="Le nom d'utilisateur que vous avez indiqué est déjà utilisé !")
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
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit faire minimum 8 caractères.")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Les mots de passes doivent être identiques")
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $prof;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Cours", inversedBy="eleves")
     */
    private $cours_eleve;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cours", mappedBy="auteur", orphanRemoval=true)
     */
    private $cours_prof;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExoUser", mappedBy="eleve", orphanRemoval=true)
     */
    private $eleve_exo;

    public function __construct()
    {
        $this->Done = new ArrayCollection();
        $this->cours_eleve = new ArrayCollection();
        $this->cours_prof = new ArrayCollection();
        $this->eleve_exo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function eraseCredentials()
    {
    }

    public function getSalt()
    {
    }

    public function getRoles()
    {
        return['ROLE_USER'];
    }

    public function getProf(): ?bool
    {
        return $this->prof;
    }

    public function setProf(bool $prof): self
    {
        $this->prof = $prof;

        return $this;
    }

    /**
     * @return Collection|Cours[]
     */
    public function getCoursEleve(): Collection
    {
        return $this->cours_eleve;
    }

    public function addCoursEleve(Cours $coursEleve): self
    {
        if (!$this->cours_eleve->contains($coursEleve)) {
            $this->cours_eleve[] = $coursEleve;
        }

        return $this;
    }

    public function removeCoursEleve(Cours $coursEleve): self
    {
        if ($this->cours_eleve->contains($coursEleve)) {
            $this->cours_eleve->removeElement($coursEleve);
        }

        return $this;
    }

    /**
     * @return Collection|Cours[]
     */
    public function getCoursProf(): Collection
    {
        return $this->cours_prof;
    }

    public function addCoursProf(Cours $coursProf): self
    {
        if (!$this->cours_prof->contains($coursProf)) {
            $this->cours_prof[] = $coursProf;
            $coursProf->setAuteur($this);
        }

        return $this;
    }

    public function removeCoursProf(Cours $coursProf): self
    {
        if ($this->cours_prof->contains($coursProf)) {
            $this->cours_prof->removeElement($coursProf);
            // set the owning side to null (unless already changed)
            if ($coursProf->getAuteur() === $this) {
                $coursProf->setAuteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ExoUser[]
     */
    public function getEleveExo(): Collection
    {
        return $this->eleve_exo;
    }

    public function addEleveExo(ExoUser $eleveExo): self
    {
        if (!$this->eleve_exo->contains($eleveExo)) {
            $this->eleve_exo[] = $eleveExo;
            $eleveExo->setEleve($this);
        }

        return $this;
    }

    public function removeEleveExo(ExoUser $eleveExo): self
    {
        if ($this->eleve_exo->contains($eleveExo)) {
            $this->eleve_exo->removeElement($eleveExo);
            // set the owning side to null (unless already changed)
            if ($eleveExo->getEleve() === $this) {
                $eleveExo->setEleve(null);
            }
        }

        return $this;
    }
}
