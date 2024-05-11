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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $complement_adr = null;

    #[ORM\Column(nullable: true)]
    private ?int $code_postal = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ville = null;

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

    public function getComplementAdr(): ?string
    {
        return $this->complement_adr;
    }

    public function setComplementAdr(?string $complement_adr): static
    {
        $this->complement_adr = $complement_adr;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->code_postal;
    }

    public function setCodePostal(?int $code_postal): static
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }
}
