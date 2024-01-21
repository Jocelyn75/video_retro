<?php

namespace App\Entity;

use App\Repository\AdrLivraisonCmdRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(mappedBy: 'adr_livraison_cmd', targetEntity: Commandes::class)]
    private Collection $commandes;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Commandes>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commandes $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setAdrLivraisonCmd($this);
        }

        return $this;
    }

    public function removeCommande(Commandes $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getAdrLivraisonCmd() === $this) {
                $commande->setAdrLivraisonCmd(null);
            }
        }

        return $this;
    }
}
