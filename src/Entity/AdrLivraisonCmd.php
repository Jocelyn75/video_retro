<?php

namespace App\Entity;

use App\Repository\AdrLivraisonCmdRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdrLivraisonCmdRepository::class)]
class AdrLivraisonCmd
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adr_livr_cmd = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdrLivrCmd(): ?string
    {
        return $this->adr_livr_cmd;
    }

    public function setAdrLivrCmd(?string $adr_livr_cmd): static
    {
        $this->adr_livr_cmd = $adr_livr_cmd;

        return $this;
    }
}
