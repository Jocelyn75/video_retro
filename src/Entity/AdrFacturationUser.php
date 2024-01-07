<?php

namespace App\Entity;

use App\Repository\AdrFacturationUserRepository;
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
}
