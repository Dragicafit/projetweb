<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Exercice;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExoUserRepository")
 */
class ExoUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="eleve_exo")
     * @ORM\JoinColumn(nullable=false)
     */
    private $eleve;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Exercice", inversedBy="exoUsers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $exercice;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbErreur;

    /**
     * @ORM\Column(type="boolean")
     */
    private $win;

    /**
     * @ORM\Column(type="boolean")
     */
    private $abandon;

    public static function initExoUser(User $user, Exercice $exercice, bool $win, EntityManagerInterface $manager)
    {
        $exoUser = $manager->getRepository(ExoUser::class)->findOneBy(['eleve' => $user,'exercice' => $exercice]);
        
        if ($exoUser == null) {
            $exoUser = new static();
            $exoUser->setEleve($user);
            $exoUser->setExercice($exercice);
            $manager->persist($exoUser);
        }

        if (!$win) {
            $exoUser->addNbErreur();
        }
        $exoUser->setWin($win);
        return $exoUser;
    }

    private function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEleve(): ?User
    {
        return $this->eleve;
    }

    public function setEleve(?User $eleve): self
    {
        $this->eleve = $eleve;

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

    public function getNbErreur(): ?int
    {
        return $this->nbErreur;
    }

    public function setNbErreur(int $nbErreur): self
    {
        $this->nbErreur = $nbErreur;

        return $this;
    }
    public function addNbErreur(): self
    {
        $this->nbErreur += 1;

        return $this;
    }

    public function getWin(): ?bool
    {
        return $this->win;
    }

    public function setWin(bool $win): self
    {
        $this->win = $win;

        return $this;
    }

    public function getAbandon(): ?bool
    {
        return $this->abandon;
    }

    public function setAbandon(bool $abandon): self
    {
        $this->abandon = $abandon;

        return $this;
    }
}
