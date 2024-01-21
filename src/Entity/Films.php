<?php

namespace App\Entity;

use App\Repository\FilmsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FilmsRepository::class)]
class Films
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $films_api_id = null;

    #[ORM\OneToMany(mappedBy: 'films', targetEntity: Stock::class)]
    private Collection $stocks;

    #[ORM\ManyToMany(targetEntity: Formats::class, inversedBy: 'films')]
    private Collection $formats;

    public function __construct()
    {
        $this->stocks = new ArrayCollection();
        $this->formats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilmsApiId(): ?int
    {
        return $this->films_api_id;
    }

    public function setFilmsApiId(?int $films_api_id): static
    {
        $this->films_api_id = $films_api_id;

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
            $stock->setFilms($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): static
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getFilms() === $this) {
                $stock->setFilms(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Formats>
     */
    public function getFormats(): Collection
    {
        return $this->formats;
    }

    public function addFormat(Formats $format): static
    {
        if (!$this->formats->contains($format)) {
            $this->formats->add($format);
        }

        return $this;
    }

    public function removeFormat(Formats $format): static
    {
        $this->formats->removeElement($format);

        return $this;
    }
}
