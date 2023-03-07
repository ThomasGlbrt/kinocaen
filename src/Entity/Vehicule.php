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

    #[ORM\Column(nullable: true)]
    private ?int $places = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $permis = null;

    #[ORM\OneToOne(inversedBy: 'vehicule', cascade: ['persist', 'remove'])]
    private ?Inscrit $inscrit = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getInscrit(): ?Inscrit
    {
        return $this->inscrit;
    }

    public function setInscrit(?Inscrit $inscrit): self
    {
        $this->inscrit = $inscrit;

        return $this;
    }
}
