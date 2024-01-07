<?php

namespace App\Entity;

use App\Repository\CommandesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandesRepository::class)]
class Commandes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $user_id = null;

    #[ORM\Column(nullable: true)]
    private ?float $montant_total = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_cmd = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statut_cmd = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $stripe_id = null;

    #[ORM\Column(nullable: true)]
    private ?bool $achat_ou_vente = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(?int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getMontantTotal(): ?float
    {
        return $this->montant_total;
    }

    public function setMontantTotal(?float $montant_total): static
    {
        $this->montant_total = $montant_total;

        return $this;
    }

    public function getDateCmd(): ?\DateTimeInterface
    {
        return $this->date_cmd;
    }

    public function setDateCmd(?\DateTimeInterface $date_cmd): static
    {
        $this->date_cmd = $date_cmd;

        return $this;
    }

    public function getStatutCmd(): ?string
    {
        return $this->statut_cmd;
    }

    public function setStatutCmd(?string $statut_cmd): static
    {
        $this->statut_cmd = $statut_cmd;

        return $this;
    }

    public function getStripeId(): ?string
    {
        return $this->stripe_id;
    }

    public function setStripeId(?string $stripe_id): static
    {
        $this->stripe_id = $stripe_id;

        return $this;
    }

    public function isAchatOuVente(): ?bool
    {
        return $this->achat_ou_vente;
    }

    public function setAchatOuVente(?bool $achat_ou_vente): static
    {
        $this->achat_ou_vente = $achat_ou_vente;

        return $this;
    }
}
