<?php

namespace App\Entity;

use App\Repository\MaterielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaterielRepository::class)]
class Materiel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $Intitule = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Description = null;

    #[ORM\ManyToMany(targetEntity: Inscrit::class, mappedBy: 'emprunt')]
    private Collection $emprunt;

    public function __construct()
    {
        $this->emprunt = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->Intitule;
    }

    public function setIntitule(string $Intitule): self
    {
        $this->Intitule = $Intitule;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    /**
     * @return Collection<int, Inscrit>
     */
    public function getEmprunt(): Collection
    {
        return $this->emprunt;
    }

    public function addEmprunt(Inscrit $emprunt): self
    {
        if (!$this->emprunt->contains($emprunt)) {
            $this->emprunt->add($emprunt);
            $emprunt->addEmprunt($this);
        }

        return $this;
    }

    public function removeEmprunt(Inscrit $emprunt): self
    {
        if ($this->emprunt->removeElement($emprunt)) {
            $emprunt->removeEmprunt($this);
        }

        return $this;
    }
}
