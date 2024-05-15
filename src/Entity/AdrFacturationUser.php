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


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $complementAdr = null;

    #[ORM\Column(nullable: true)]
    private ?int $codePostal = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ville = null;

    #[ORM\OneToOne(mappedBy: 'adr_facturation_user', cascade: ['persist', 'remove'])]
    private ?User $user = null;

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



    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getComplementAdr(): ?string
    {
        return $this->complementAdr;
    }

    public function setComplementAdr(?string $complementAdr): static
    {
        $this->complementAdr = $complementAdr;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->codePostal;
    }

    public function setCodePostal(?int $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setAdrFacturationUser(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getAdrFacturationUser() !== $this) {
            $user->setAdrFacturationUser($this);
        }

        $this->user = $user;

        return $this;
    }

}
