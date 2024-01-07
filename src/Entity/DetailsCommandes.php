<?php

namespace App\Entity;

use App\Repository\DetailsCommandesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetailsCommandesRepository::class)]
class DetailsCommandes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $commandes_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $stock_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantite_cmd = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix_unitaire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommandesId(): ?int
    {
        return $this->commandes_id;
    }

    public function setCommandesId(?int $commandes_id): static
    {
        $this->commandes_id = $commandes_id;

        return $this;
    }

    public function getStockId(): ?int
    {
        return $this->stock_id;
    }

    public function setStockId(?int $stock_id): static
    {
        $this->stock_id = $stock_id;

        return $this;
    }

    public function getQuantiteCmd(): ?int
    {
        return $this->quantite_cmd;
    }

    public function setQuantiteCmd(?int $quantite_cmd): static
    {
        $this->quantite_cmd = $quantite_cmd;

        return $this;
    }

    public function getPrixUnitaire(): ?float
    {
        return $this->prix_unitaire;
    }

    public function setPrixUnitaire(?float $prix_unitaire): static
    {
        $this->prix_unitaire = $prix_unitaire;

        return $this;
    }
}
