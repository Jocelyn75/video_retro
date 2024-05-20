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

    #[ORM\OneToOne(inversedBy: 'adr_livraison_cmd', cascade: ['persist', 'remove'])]
    private ?Commandes $commandes = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $complement_adr = null;

    #[ORM\Column(nullable: true)]
    private ?int $code_postal = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ville = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom = null;

    public function __construct()
    {
        // $this->commandes = new ArrayCollection();
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

    public function getCommandes(): ?Commandes
    {
        return $this->commandes;
    }

    public function setCommandes(?Commandes $commandes): static
    {
        $this->commandes = $commandes;

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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }
}
