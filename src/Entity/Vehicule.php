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

    #[ORM\OneToOne(mappedBy: 'Vehicule', cascade: ['persist', 'remove'])]
    private ?Inscrit $inscrit_Id = null;

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

    public function getInscritId(): ?Inscrit
    {
        return $this->inscrit_Id;
    }

    public function setInscritId(?Inscrit $inscrit_Id): self
    {
        // unset the owning side of the relation if necessary
        if ($inscrit_Id === null && $this->inscrit_Id !== null) {
            $this->inscrit_Id->setVehicule(null);
        }

        // set the owning side of the relation if necessary
        if ($inscrit_Id !== null && $inscrit_Id->getVehicule() !== $this) {
            $inscrit_Id->setVehicule($this);
        }

        $this->inscrit_Id = $inscrit_Id;

        return $this;
    }
}