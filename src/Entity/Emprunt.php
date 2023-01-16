<?php

namespace App\Entity;

use App\Repository\EmpruntRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmpruntRepository::class)]
class Emprunt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\ManyToMany(targetEntity: Inscrit::class, inversedBy: 'emprunts')]
    private Collection $inscrit;

    public function __construct()
    {
        $this->inscrit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * @return Collection<int, Inscrit>
     */
    public function getInscrit(): Collection
    {
        return $this->inscrit;
    }

    public function addInscrit(Inscrit $inscrit): self
    {
        if (!$this->inscrit->contains($inscrit)) {
            $this->inscrit->add($inscrit);
        }

        return $this;
    }

    public function removeInscrit(Inscrit $inscrit): self
    {
        $this->inscrit->removeElement($inscrit);

        return $this;
    }
}
