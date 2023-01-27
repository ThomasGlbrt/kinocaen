<?php

namespace App\Entity;

use App\Repository\TypePaiementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypePaiementRepository::class)]
class TypePaiement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $montant = null;

    #[ORM\OneToMany(mappedBy: 'Paiement', targetEntity: Inscrit::class)]
    private Collection $inscrits;

    #[ORM\OneToMany(mappedBy: 'Paiement', targetEntity: Inscrit::class)]
    private Collection $inscritId;

    public function __construct()
    {
        $this->inscrits = new ArrayCollection();
        $this->inscritId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * @return Collection<int, Inscrit>
     */
    public function getInscritId(): Collection
    {
        return $this->inscritId;
    }

    public function addInscritId(Inscrit $inscritId): self
    {
        if (!$this->inscritId->contains($inscritId)) {
            $this->inscritId->add($inscritId);
            $inscritId->setPaiement($this);
        }

        return $this;
    }

    public function removeInscritId(Inscrit $inscritId): self
    {
        if ($this->inscritId->removeElement($inscritId)) {
            // set the owning side to null (unless already changed)
            if ($inscritId->getPaiement() === $this) {
                $inscritId->setPaiement(null);
            }
        }

        return $this;
    }

}
