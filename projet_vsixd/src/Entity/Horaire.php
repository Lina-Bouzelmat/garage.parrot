<?php

namespace App\Entity;

use App\Repository\HoraireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HoraireRepository::class)]
class Horaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $jour = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $matin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $soir = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJour(): ?string
    {
        return $this->jour;
    }

    public function setJour(string $jour): static
    {
        $this->jour = $jour;

        return $this;
    }

    public function getMatin(): ?string
    {
        return $this->matin;
    }

    public function setMatin(?string $matin): static
    {
        $this->matin = $matin;

        return $this;
    }

    public function getSoir(): ?string
    {
        return $this->soir;
    }

    public function setSoir(?string $soir): static
    {
        $this->soir = $soir;

        return $this;
    }
}
