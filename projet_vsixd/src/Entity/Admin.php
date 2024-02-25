<?php

namespace App\Entity;

use App\Entity\Temoignage;
use App\Repository\AdminRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
class Admin implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<Temoignage>
     * @ORM\OneToMany(targetEntity=Temoignage::class, mappedBy="admin")
     */
    private Collection $isPublished;

    public function __construct()
    {
        $this->isPublished = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    


    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_ADMIN';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<Temoignage>
     */
    public function getIsPublished(): Collection
    {
        return $this->isPublished;
    }

    public function addIsPublished(Temoignage $temoignage): self
    {
        // Vérifiez si le témoignage n'est pas déjà associé à cet administrateur
        if (!$this->isPublished->contains($temoignage)) {
            // Ajoutez le témoignage à la collection
            $this->isPublished[] = $temoignage;
            // Associez l'administrateur au témoignage
            $temoignage->setEmploye($this); // Assurez-vous que la méthode setEmploye est correctement définie dans votre entité Temoignage
        }
    
        return $this;
    }
    public function removeIsPublished(Temoignage $temoignage): self
    {
        if ($this->isPublished->removeElement($temoignage)) {
            // set the owning side to null (unless already changed)
            if ($temoignage->getEmploye() === $this) {
                $temoignage->setEmploye(null);
            }
        }

        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}