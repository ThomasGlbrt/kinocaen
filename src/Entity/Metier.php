<?php

namespace App\Entity;

use App\Repository\MetierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MetierRepository::class)]
class Metier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\ManyToMany(targetEntity: Inscrit::class, mappedBy: 'inscrit_metier')]
    private Collection $metier_id;

    #[ORM\ManyToMany(targetEntity: Inscrit::class, mappedBy: 'metier')]
    private Collection $inscrit_metier;

    public function __construct()
    {
        $this->metier_id = new ArrayCollection();
        $this->inscrit_metier = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Inscrit>
     */
    public function getMetierId(): Collection
    {
        return $this->metier_id;
    }

    public function addMetierId(Inscrit $metierId): self
    {
        if (!$this->metier_id->contains($metierId)) {
            $this->metier_id->add($metierId);
            $metierId->addInscritMetier($this);
        }

        return $this;
    }

    public function removeMetierId(Inscrit $metierId): self
    {
        if ($this->metier_id->removeElement($metierId)) {
            $metierId->removeInscritMetier($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Inscrit>
     */
    public function getInscritMetier(): Collection
    {
        return $this->inscrit_metier;
    }

    public function addInscritMetier(Inscrit $inscritMetier): self
    {
        if (!$this->inscrit_metier->contains($inscritMetier)) {
            $this->inscrit_metier->add($inscritMetier);
            $inscritMetier->addMetier($this);
        }

        return $this;
    }

    public function removeInscritMetier(Inscrit $inscritMetier): self
    {
        if ($this->inscrit_metier->removeElement($inscritMetier)) {
            $inscritMetier->removeMetier($this);
        }

        return $this;
    }
}
