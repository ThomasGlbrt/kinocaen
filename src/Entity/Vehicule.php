<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
class Vehicule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?inscrit $inscrit = null;

    #[ORM\Column(nullable: true)]
    private ?int $places = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $permis = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInscrit(): ?inscrit
    {
        return $this->inscrit;
    }

    public function setInscrit(?inscrit $inscrit): self
    {
        $this->inscrit = $inscrit;

        return $this;
    }

    public function getPlaces(): ?int
    {
        return $this->places;
    }

    public function setPlaces(?int $places): self
    {
        $this->places = $places;

        return $this;
    }

    public function getPermis(): ?string
    {
        return $this->permis;
    }

    public function setPermis(?string $permis): self
    {
        $this->permis = $permis;

        return $this;
    }
}
