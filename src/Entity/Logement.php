<?php

namespace App\Entity;

use App\Repository\LogementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LogementRepository::class)]
class Logement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $besoin = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Descriptif = null;

    #[ORM\OneToOne(mappedBy: 'logementId', cascade: ['persist', 'remove'])]
    private ?Inscrit $inscrit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBesoin(): ?int
    {
        return $this->besoin;
    }

    public function setBesoin(?int $besoin): self
    {
        $this->besoin = $besoin;

        return $this;
    }

    public function getDescriptif(): ?string
    {
        return $this->Descriptif;
    }

    public function setDescriptif(?string $Descriptif): self
    {
        $this->Descriptif = $Descriptif;

        return $this;
    }

    public function getInscrit(): ?Inscrit
    {
        return $this->inscrit;
    }

    public function setInscrit(?Inscrit $inscrit): self
    {
        // unset the owning side of the relation if necessary
        if ($inscrit === null && $this->inscrit !== null) {
            $this->inscrit->setLogementId(null);
        }

        // set the owning side of the relation if necessary
        if ($inscrit !== null && $inscrit->getLogementId() !== $this) {
            $inscrit->setLogementId($this);
        }

        $this->inscrit = $inscrit;

        return $this;
    }
}
