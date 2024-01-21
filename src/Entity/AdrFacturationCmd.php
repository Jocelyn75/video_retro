<?php

namespace App\Entity;

use App\Repository\AdrFacturationCmdRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(mappedBy: 'adr_facturation_cmd', targetEntity: Commandes::class)]
    private Collection $commandes;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
    }

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
            $commande->setAdrFacturationCmd($this);
        }

        return $this;
    }

    public function removeCommande(Commandes $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getAdrFacturationCmd() === $this) {
                $commande->setAdrFacturationCmd(null);
            }
        }

        return $this;
    }
}
