<?php

namespace App\Entity;

use App\Entity\Solution;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

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
    private $solution;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Ligne", inversedBy="exercices")
     */
    private $ligne;

    public function initExercice(string $solutionRaw, string $consigne, EntityManagerInterface $manager)
    {
        $this->setConsigne($consigne)->parseSolution($solutionRaw, $manager);
        $manager->persist($this);

        return $this;
    }

    public function __construct()
    {
        $this->solution = new ArrayCollection();
        $this->ligne = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
    public function getSolution(): Collection
    {
        return $this->solution;
    }

    public function addSolution(Solution $solution): self
    {
        if (!$this->solution->contains($solution)) {
            $this->solution[] = $solution;
            $solution->setExercice($this);
        }

        return $this;
    }

    public function removeSolution(Solution $solution): self
    {
        if ($this->solution->contains($solution)) {
            $this->solution->removeElement($solution);
            // set the owning side to null (unless already changed)
            if ($solution->getExercice() === $this) {
                $solution->setExercice(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ligne[]
     */
    public function getLigne(): Collection
    {
        return $this->ligne;
    }

    public function addLigne(Ligne $ligne): self
    {
        if (!$this->ligne->contains($ligne)) {
            $this->ligne[] = $ligne;
        }

        return $this;
    }

    public function removeLigne(Ligne $ligne): self
    {
        if ($this->ligne->contains($ligne)) {
            $this->ligne->removeElement($ligne);
        }

        return $this;
    }

    public function parseSolution(string $solutionRaw, EntityManagerInterface $manager)
    {
        $values = str_replace("\r", "", $solutionRaw);
        $values = str_replace("\t", "    ", $values);

        preg_match_all('/^(?<spaces> *)(?<text>[^ ].*)$/m', $values, $matches);

        $result = $matches['spaces'];
        $values = $matches['text'];

        $count=[];
        foreach ($result as $key => $value) {
            $count[$key] = strlen($value);
        }
        $count2=array_unique($count, SORT_NUMERIC);
        sort($count2);
        $convert = array_flip($count2);

        $solution = Solution::initSolution($values, $count, $convert, $this, $manager);
        $this->addSolution($solution);

        return $this;
    }
}
