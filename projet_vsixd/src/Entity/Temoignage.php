<?php

namespace App\Entity;

use App\Repository\TemoignageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TemoignageRepository::class)]
class Temoignage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $commentaire = null;

    #[ORM\Column]
    private ?int $note = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'isPublished')]
    private ?self $employe = null;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'employe')]
    private Collection $isPublished;

    public function __construct()
    {
        $this->isPublished = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getEmploye(): ?self
    {
        return $this->employe;
    }

    public function setEmploye(?Admin $employe): static
    {
        $this->employe = $employe;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getIsPublished(): Collection
    {
        return $this->isPublished;
    }

    

    public function removeIsPublished(self $isPublished): static
    {
        if ($this->isPublished->removeElement($isPublished)) {
            // set the owning side to null (unless already changed)
            if ($isPublished->getEmploye() === $this) {
                $isPublished->setEmploye(null);
            }
        }

        return $this;
    }
}
