<?php

namespace App\Entity;

use Exception;
use App\Entity\Ligne;
use App\Entity\Exercice;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SolutionRepository")
 */
class Solution
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tab", mappedBy="solution", orphanRemoval=true)
     */
    private $tab;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Exercice", inversedBy="solution")
     * @ORM\JoinColumn(nullable=false)
     */
    private $exercice;

    public static function initSolution(array $lignes, Exercice $exo, EntityManagerInterface $manager)
    {
        $solution = new static();
        foreach ($lignes as $key => $ligne) {
            $tab = Tab::initTab($ligne["ligne"], $ligne["tab"], $manager);
            $solution->addTab($tab);
        }
        $manager->persist($solution);

        return $solution;
    }

    private function __construct()
    {
        $this->tab = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Tab[]
     */
    public function getTab(): Collection
    {
        return $this->tab;
    }

    public function addTab(Tab $tab): self
    {
        if (!$this->tab->contains($tab)) {
            $this->tab[] = $tab;
            $tab->setSolution($this);
        }

        return $this;
    }

    public function removeTab(Tab $tab): self
    {
        if ($this->tab->contains($tab)) {
            $this->tab->removeElement($tab);
            // set the owning side to null (unless already changed)
            if ($tab->getSolution() === $this) {
                $tab->setSolution(null);
            }
        }

        return $this;
    }

    public function getExercice(): ?Exercice
    {
        return $this->exercice;
    }

    public function setExercice(?Exercice $exercice): self
    {
        $this->exercice = $exercice;

        return $this;
    }
}
