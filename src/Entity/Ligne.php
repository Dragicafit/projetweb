<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LigneRepository")
 */
class Ligne
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $text;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tab", mappedBy="id_ligne", orphanRemoval=true)
     */
    private $tabs;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Exercice", mappedBy="id_ligne")
     */
    private $id_exercices;

    public static function initLigne(string $text)
    {
        $ligne = new static();
        $ligne->setText($text);
        return $ligne;
    }

    private function __construct()
    {
        $this->tabs = new ArrayCollection();
        $this->id_exercices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return Collection|Tab[]
     */
    public function getTabs(): Collection
    {
        return $this->tabs;
    }

    public function addTab(Tab $tab): self
    {
        if (!$this->tabs->contains($tab)) {
            $this->tabs[] = $tab;
            $tab->setIdLigne($this);
        }

        return $this;
    }

    public function removeTab(Tab $tab): self
    {
        if ($this->tabs->contains($tab)) {
            $this->tabs->removeElement($tab);
            // set the owning side to null (unless already changed)
            if ($tab->getIdLigne() === $this) {
                $tab->setIdLigne(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Exercice[]
     */
    public function getExercices(): Collection
    {
        return $this->id_exercices;
    }

    public function addIdExercice(Exercice $idExercice): self
    {
        if (!$this->id_exercices->contains($idExercice)) {
            $this->id_exercices[] = $idExercice;
            $idExercice->addIdLigne($this);
        }

        return $this;
    }

    public function removeIdExercice(Exercice $idExercice): self
    {
        if ($this->id_exercices->contains($idExercice)) {
            $this->id_exercices->removeElement($idExercice);
            $idExercice->removeIdLigne($this);
        }

        return $this;
    }
}
