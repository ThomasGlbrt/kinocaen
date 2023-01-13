<?php

namespace App\Entity;

use App\Repository\InscritRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InscritRepository::class)]
class Inscrit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    #[ORM\OneToOne(mappedBy: 'inscrit', cascade: ['persist', 'remove'])]
    private ?Utilisateur $utilisateurs = null;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $poste = null;

    #[ORM\Column(nullable: true)]
    private ?int $numTel = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $Talent = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ChosePlus = null;

    #[ORM\ManyToMany(targetEntity: metier::class, inversedBy: 'metier_id')]
    private Collection $inscrit_metier;

    #[ORM\Column(length: 250, nullable: true)]
    private ?string $image = null;

    public function __construct()
    {
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getUtilisateurs(): ?Utilisateur
    {
        return $this->utilisateurs;
    }

    public function setUtilisateurs(?Utilisateur $utilisateurs): self
    {
        // unset the owning side of the relation if necessary
        if ($utilisateurs === null && $this->utilisateurs !== null) {
            $this->utilisateurs->setInscrit(null);
        }

        // set the owning side of the relation if necessary
        if ($utilisateurs !== null && $utilisateurs->getInscrit() !== $this) {
            $utilisateurs->setInscrit($this);
        }

        $this->utilisateurs = $utilisateurs;

        return $this;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(?string $poste): self
    {
        $this->poste = $poste;

        return $this;
    }

    public function getNumTel(): ?int
    {
        return $this->numTel;
    }

    public function setNumTel(?int $numTel): self
    {
        $this->numTel = $numTel;

        return $this;
    }

    public function getTalent(): ?string
    {
        return $this->Talent;
    }

    public function setTalent(?string $Talent): self
    {
        $this->Talent = $Talent;

        return $this;
    }

    public function getChosePlus(): ?string
    {
        return $this->ChosePlus;
    }

    public function setChosePlus(?string $ChosePlus): self
    {
        $this->ChosePlus = $ChosePlus;

        return $this;
    }

    /**
     * @return Collection<int, metier>
     */
    public function getInscritMetier(): Collection
    {
        return $this->inscrit_metier;
    }

    public function addInscritMetier(metier $inscritMetier): self
    {
        if (!$this->inscrit_metier->contains($inscritMetier)) {
            $this->inscrit_metier->add($inscritMetier);
        }

        return $this;
    }

    public function removeInscritMetier(metier $inscritMetier): self
    {
        $this->inscrit_metier->removeElement($inscritMetier);

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
