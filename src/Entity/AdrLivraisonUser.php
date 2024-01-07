<?php

namespace App\Entity;

use App\Repository\AdrLivraisonUserRepository;
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
}
