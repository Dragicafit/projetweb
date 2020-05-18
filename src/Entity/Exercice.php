<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExerciceRepository")
 */
class Exercice
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="array")
     */
    private $exo = [];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cours", inversedBy="exercices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cour;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $consigne;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Solution", mappedBy="exercice", orphanRemoval=true)
     */
    private $id_solution;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Ligne", inversedBy="id_exercices")
     */
    private $id_ligne;

    public function __construct()
    {
        $this->id_solution = new ArrayCollection();
        $this->id_ligne = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExo(): ?array
    {
        return $this->exo;
    }

    public function setExo(array $exo): self
    {
        $this->exo = $exo;

        return $this;
    }

    public function getCour(): ?Cours
    {
        return $this->cour;
    }

    public function setCour(?Cours $cour): self
    {
        $this->cour = $cour;

        return $this;
    }

    public function getConsigne(): ?string
    {
        return $this->consigne;
    }

    public function setConsigne(string $consigne): self
    {
        $this->consigne = $consigne;

        return $this;
    }

    /**
     * @return Collection|Solution[]
     */
    public function getIdSolution(): Collection
    {
        return $this->id_solution;
    }

    public function addIdSolution(Solution $idSolution): self
    {
        if (!$this->id_solution->contains($idSolution)) {
            $this->id_solution[] = $idSolution;
            $idSolution->setExercice($this);
        }

        return $this;
    }

    public function removeIdSolution(Solution $idSolution): self
    {
        if ($this->id_solution->contains($idSolution)) {
            $this->id_solution->removeElement($idSolution);
            // set the owning side to null (unless already changed)
            if ($idSolution->getExercice() === $this) {
                $idSolution->setExercice(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ligne[]
     */
    public function getIdLigne(): Collection
    {
        return $this->id_ligne;
    }

    public function addIdLigne(Ligne $idLigne): self
    {
        if (!$this->id_ligne->contains($idLigne)) {
            $this->id_ligne[] = $idLigne;
        }

        return $this;
    }

    public function removeIdLigne(Ligne $idLigne): self
    {
        if ($this->id_ligne->contains($idLigne)) {
            $this->id_ligne->removeElement($idLigne);
        }

        return $this;
    }
}
