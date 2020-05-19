<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

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
    private $ligne;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Solution", inversedBy="tab")
     * @ORM\JoinColumn(nullable=false)
     */
    private $solution;

    public static function initTab(Ligne $ligne, int $nb_tab, EntityManagerInterface $manager)
    {
        $tab = new static();
        $tab->setLigne($ligne)->setNbTab($nb_tab);
        $manager->persist($tab);

        return $tab;
    }

    private function __construct()
    {
    }

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

    public function getLigne(): ?Ligne
    {
        return $this->ligne;
    }

    public function setLigne(?Ligne $ligne): self
    {
        $this->ligne = $ligne;

        return $this;
    }

    public function getSolution(): ?Solution
    {
        return $this->solution;
    }

    public function setSolution(?Solution $solution): self
    {
        $this->solution = $solution;

        return $this;
    }
}
