<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CoursRepository")
 */
class Cours
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="cours_prof")
     * @ORM\JoinColumn(nullable=false)
     */
    private $auteur;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $temps;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Exercice", mappedBy="cour", orphanRemoval=true)
     */
    private $exercices;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="cours_eleve")
     */
    private $eleves;


    public function __construct()
    {
        $this->exercices = new ArrayCollection();
        $this->eleves = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getTemps(): ?int
    {
        return $this->temps;
    }

    public function setTemps(?int $temps): self
    {
        $this->temps = $temps;

        return $this;
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

    /**
     * @return Collection|Exercice[]
     */
    public function getExercices(): Collection
    {
        return $this->exercices;
    }

    public function addExercice(Exercice $exercice): self
    {
        if (!$this->exercices->contains($exercice)) {
            $this->exercices[] = $exercice;
            $exercice->setCour($this);
        }

        return $this;
    }

    public function removeExercice(Exercice $exercice): self
    {
        if ($this->exercices->contains($exercice)) {
            $this->exercices->removeElement($exercice);
            // set the owning side to null (unless already changed)
            if ($exercice->getCour() === $this) {
                $exercice->setCour(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getEleves(): Collection
    {
        return $this->eleves;
    }

    public function addEleve(User $eleve): self
    {
        if (!$this->eleves->contains($eleve)) {
            $this->eleves[] = $eleve;
            $eleve->addCoursEleve($this);
        }

        return $this;
    }

    public function removeEleve(User $eleve): self
    {
        if ($this->eleves->contains($eleve)) {
            $this->eleves->removeElement($eleve);
            $eleve->removeCoursEleve($this);
        }

        return $this;
    }
}
