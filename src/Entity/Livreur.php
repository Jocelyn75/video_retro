<?php

namespace App\Entity;

use App\Repository\LivreurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivreurRepository::class)]
class Livreur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom_livreur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomLivreur(): ?string
    {
        return $this->nom_livreur;
    }

    public function setNomLivreur(?string $nom_livreur): static
    {
        $this->nom_livreur = $nom_livreur;

        return $this;
    }
}
