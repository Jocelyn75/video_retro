<?php

namespace App\Entity;

use App\Repository\FormatsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(mappedBy: 'formats', targetEntity: Stock::class)]
    private Collection $stocks;

    #[ORM\ManyToMany(targetEntity: Films::class, mappedBy: 'formats')]
    private Collection $films;

    public function __construct()
    {
        $this->stocks = new ArrayCollection();
        $this->films = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Stock>
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function addStock(Stock $stock): static
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks->add($stock);
            $stock->setFormats($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): static
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getFormats() === $this) {
                $stock->setFormats(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Films>
     */
    public function getFilms(): Collection
    {
        return $this->films;
    }

    public function addFilm(Films $film): static
    {
        if (!$this->films->contains($film)) {
            $this->films->add($film);
            $film->addFormat($this);
        }

        return $this;
    }

    public function removeFilm(Films $film): static
    {
        if ($this->films->removeElement($film)) {
            $film->removeFormat($this);
        }

        return $this;
    }
}
