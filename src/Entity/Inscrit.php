<?php

namespace App\Entity;

use App\Repository\InscritRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToMany(targetEntity: materiel::class, inversedBy: 'emprunt')]
    private Collection $emprunt;

    public function __construct()
    {
        $this->emprunt = new ArrayCollection();
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

    /**
     * @return Collection<int, materiel>
     */
    public function getEmprunt(): Collection
    {
        return $this->emprunt;
    }

    public function addEmprunt(materiel $emprunt): self
    {
        if (!$this->emprunt->contains($emprunt)) {
            $this->emprunt->add($emprunt);
        }

        return $this;
    }

    public function removeEmprunt(materiel $emprunt): self
    {
        $this->emprunt->removeElement($emprunt);

        return $this;
    }
}
