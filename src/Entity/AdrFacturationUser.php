<?php

namespace App\Entity;

use App\Repository\AdrFacturationUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdrFacturationUserRepository::class)]
class AdrFacturationUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adr_fact_user = null;

    #[ORM\OneToMany(mappedBy: 'adr_facturation_user', targetEntity: User::class)]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdrFactUser(): ?string
    {
        return $this->adr_fact_user;
    }

    public function setAdrFactUser(?string $adr_fact_user): static
    {
        $this->adr_fact_user = $adr_fact_user;

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
            $user->setAdrFacturationUser($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAdrFacturationUser() === $this) {
                $user->setAdrFacturationUser(null);
            }
        }

        return $this;
    }
}
