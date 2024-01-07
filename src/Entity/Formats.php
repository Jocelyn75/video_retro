<?php

namespace App\Entity;

use App\Repository\FormatsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormatsRepository::class)]
class Formats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 7, nullable: true)]
    private ?string $nom_format = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix_rachat_defaut = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFormat(): ?string
    {
        return $this->nom_format;
    }

    public function setNomFormat(?string $nom_format): static
    {
        $this->nom_format = $nom_format;

        return $this;
    }

    public function getPrixRachatDefaut(): ?float
    {
        return $this->prix_rachat_defaut;
    }

    public function setPrixRachatDefaut(?float $prix_rachat_defaut): static
    {
        $this->prix_rachat_defaut = $prix_rachat_defaut;

        return $this;
    }
}
