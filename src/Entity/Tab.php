<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TabRepository")
 */
class Tab
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
    private $nb_tab;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ligne", inversedBy="tabs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_ligne;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Solution", inversedBy="id_tab")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_solution;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbTab(): ?int
    {
        return $this->nb_tab;
    }

    public function setNbTab(int $nb_tab): self
    {
        $this->nb_tab = $nb_tab;

        return $this;
    }

    public function getIdLigne(): ?Ligne
    {
        return $this->id_ligne;
    }

    public function setIdLigne(?Ligne $id_ligne): self
    {
        $this->id_ligne = $id_ligne;

        return $this;
    }

    public function getIdSolution(): ?Solution
    {
        return $this->id_solution;
    }

    public function setIdSolution(?Solution $id_solution): self
    {
        $this->id_solution = $id_solution;

        return $this;
    }
}
