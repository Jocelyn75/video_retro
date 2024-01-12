<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockRepository::class)]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $formats_id = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix_revente_defaut = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantite_stock = null;

    #[ORM\Column(nullable: true)]
    private ?int $films_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFormatsId(): ?int
    {
        return $this->formats_id;
    }

    public function setFormatsId(?int $formats_id): static
    {
        $this->formats_id = $formats_id;

        return $this;
    }

    public function getPrixReventeDefaut(): ?float
    {
        return $this->prix_revente_defaut;
    }

    public function setPrixReventeDefaut(?float $prix_revente_defaut): static
    {
        $this->prix_revente_defaut = $prix_revente_defaut;

        return $this;
    }

    public function getQuantiteStock(): ?int
    {
        return $this->quantite_stock;
    }

    public function setQuantiteStock(?int $quantite_stock): static
    {
        $this->quantite_stock = $quantite_stock;

        return $this;
    }

    public function getFilmsId(): ?int
    {
        return $this->films_id;
    }

    public function setFilmsId(?int $films_id): static
    {
        $this->films_id = $films_id;

        return $this;
    }
}
