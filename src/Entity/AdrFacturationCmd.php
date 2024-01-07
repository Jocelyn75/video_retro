<?php

namespace App\Entity;

use App\Repository\AdrFacturationCmdRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdrFacturationCmdRepository::class)]
class AdrFacturationCmd
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adr_fact_cmd = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdrFactCmd(): ?string
    {
        return $this->adr_fact_cmd;
    }

    public function setAdrFactCmd(?string $adr_fact_cmd): static
    {
        $this->adr_fact_cmd = $adr_fact_cmd;

        return $this;
    }
}
