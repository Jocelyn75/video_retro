<?php

namespace App\Entity;

use App\Repository\FilmsRepository;
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
}
