<?php

namespace App\Entity;

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
}
