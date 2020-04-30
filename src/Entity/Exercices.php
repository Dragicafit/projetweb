<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExercicesRepository")
 */
class Exercices
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $exo_id;

    /**
     * @ORM\Column(type="array")
     */
    private $value = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExoId(): ?int
    {
        return $this->exo_id;
    }

    public function setExoId(int $exo_id): self
    {
        $this->exo_id = $exo_id;

        return $this;
    }

    public function getValue(): ?array
    {
        return $this->value;
    }

    public function setValue(array $value): self
    {
        $this->value = $value;

        return $this;
    }
}
