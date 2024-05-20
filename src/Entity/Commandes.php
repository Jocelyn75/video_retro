<?php

namespace App\Entity;

use App\Repository\CommandesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?User $user = null;

    #[ORM\OneToOne(mappedBy: 'commandes', cascade: ['persist', 'remove'])]
    private ?AdrFacturationCmd $adr_facturation_cmd = null;

    #[ORM\OneToOne(mappedBy: 'commandes', cascade: ['persist', 'remove'])]
    private ?AdrLivraisonCmd $adr_livraison_cmd = null;

    #[ORM\ManyToOne(inversedBy: 'commandes', cascade: ['persist', 'remove'])]
    private ?Livreur $livreur = null;

    #[ORM\OneToMany(mappedBy: 'commandes', targetEntity: DetailsCommandes::class, orphanRemoval:true, cascade:['persist'])]
    private Collection $details_commandes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reference = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->details_commandes = new ArrayCollection();
    }

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    // public function getAdrFacturationCmd(): ?AdrFacturationCmd
    // {
    //     return $this->adr_facturation_cmd;
    // }

    // public function setAdrFacturationCmd(?AdrFacturationCmd $adr_facturation_cmd): static
    // {
    //     $this->adr_facturation_cmd = $adr_facturation_cmd;

    //     return $this;
    // }

    // public function getAdrLivraisonCmd(): ?AdrLivraisonCmd
    // {
    //     return $this->adr_livraison_cmd;
    // }

    // public function setAdrLivraisonCmd(?AdrLivraisonCmd $adr_livraison_cmd): static
    // {
    //     $this->adr_livraison_cmd = $adr_livraison_cmd;

    //     return $this;
    // }

    public function getLivreur(): ?Livreur
    {
        return $this->livreur;
    }

    public function setLivreur(?Livreur $livreur): static
    {
        $this->livreur = $livreur;

        return $this;
    }

    /**
     * @return Collection<int, DetailsCommandes>
     */
    public function getDetailsCommandes(): Collection
    {
        return $this->details_commandes;
    }

    public function addDetailsCommande(DetailsCommandes $detailsCommande): static
    {
        if (!$this->details_commandes->contains($detailsCommande)) {
            $this->details_commandes->add($detailsCommande);
            $detailsCommande->setCommandes($this);
        }

        return $this;
    }

    public function removeDetailsCommande(DetailsCommandes $detailsCommande): static
    {
        if ($this->details_commandes->removeElement($detailsCommande)) {
            // set the owning side to null (unless already changed)
            if ($detailsCommande->getCommandes() === $this) {
                $detailsCommande->setCommandes(null);
            }
        }

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAdrLivraisonCmd(): ?AdrLivraisonCmd
    {
        return $this->adr_livraison_cmd;
    }

    public function setAdrLivraisonCmd(?AdrLivraisonCmd $adr_livraison_cmd): static
    {
        // unset the owning side of the relation if necessary
        if ($adr_livraison_cmd === null && $this->adr_livraison_cmd !== null) {
            $this->adr_livraison_cmd->setCommandes(null);
        }

        // set the owning side of the relation if necessary
        if ($adr_livraison_cmd !== null && $adr_livraison_cmd->getCommandes() !== $this) {
            $adr_livraison_cmd->setCommandes($this);
        }

        $this->adr_livraison_cmd = $adr_livraison_cmd;

        return $this;
    }

    public function getAdrFacturationCmd(): ?AdrFacturationCmd
    {
        return $this->adr_facturation_cmd;
    }

    public function setAdrFacturationCmd(?AdrFacturationCmd $adr_facturation_cmd): static
    {
        // unset the owning side of the relation if necessary
        if ($adr_facturation_cmd === null && $this->adr_facturation_cmd !== null) {
            $this->adr_facturation_cmd->setCommandes(null);
        }

        // set the owning side of the relation if necessary
        if ($adr_facturation_cmd !== null && $adr_facturation_cmd->getCommandes() !== $this) {
            $adr_facturation_cmd->setCommandes($this);
        }

        $this->adr_facturation_cmd = $adr_facturation_cmd;

        return $this;
    }
}
