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
    private $id_tab;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Exercice", inversedBy="id_solution")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_exercice;

    public static function initSolution(array $values, array $count, array $convert, Exercice $exo, EntityManagerInterface $manager)
    {
        $solution = new static();

        $lignes = [];

        foreach ($values as $key => $value) {
            $ligne = Ligne::initLigne($value, $manager);
            $exo->addIdLigne($ligne);
            $lignes[$key] = $ligne;
            $manager->flush();
        }
        foreach ($values as $key => $value) {
            $tab = Tab::initTab($lignes[$key], $convert[$count[$key]], $manager);
            $solution->addIdTab($tab);
        }
        $manager->persist($solution);

        return $solution;
    }

    private function __construct()
    {
        $this->id_tab = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Tab[]
     */
    public function getIdTab(): Collection
    {
        return $this->id_tab;
    }

    public function addIdTab(Tab $idTab): self
    {
        if (!$this->id_tab->contains($idTab)) {
            $this->id_tab[] = $idTab;
            $idTab->setSolution($this);
        }

        return $this;
    }

    public function removeIdTab(Tab $idTab): self
    {
        if ($this->id_tab->contains($idTab)) {
            $this->id_tab->removeElement($idTab);
            // set the owning side to null (unless already changed)
            if ($idTab->getSolution() === $this) {
                $idTab->setSolution(null);
            }
        }

        return $this;
    }

    public function getExercice(): ?Exercice
    {
        return $this->id_exercice;
    }

    public function setExercice(?Exercice $id_exercice): self
    {
        $this->id_exercice = $id_exercice;

        return $this;
    }
}
