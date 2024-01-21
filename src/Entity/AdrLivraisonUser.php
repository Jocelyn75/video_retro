<?php

namespace App\Entity;

use App\Repository\AdrLivraisonUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdrLivraisonUserRepository::class)]
class AdrLivraisonUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adr_livr_user = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'adr_livraison_user')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdrLivrUser(): ?string
    {
        return $this->adr_livr_user;
    }

    public function setAdrLivrUser(?string $adr_livr_user): static
    {
        $this->adr_livr_user = $adr_livr_user;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addAdrLivraisonUser($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeAdrLivraisonUser($this);
        }

        return $this;
    }
}
