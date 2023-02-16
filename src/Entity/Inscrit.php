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

    #[ORM\Column(length: 250, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToMany(targetEntity: metier::class, inversedBy: 'inscrits')]
    private Collection $metier;

    #[ORM\OneToMany(mappedBy: 'inscrit', targetEntity: Emprunt::class)]
    private Collection $emprunt;

    #[ORM\ManyToOne(inversedBy: 'inscritId')]
    private ?typePaiement $Paiement = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $facebook = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $pays = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $ville = null;

    #[ORM\Column(nullable: true)]
    private ?int $premierKabaret = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $matosDispo = null;

    #[ORM\Column(nullable: true)]
    private ?int $permis = null;

    #[ORM\Column(nullable: true)]
    private ?int $vehicule = null;

    #[ORM\OneToOne(inversedBy: 'inscrit', cascade: ['persist', 'remove'])]
    private ?logement $logementId = null;

    #[ORM\ManyToMany(targetEntity: typeCompetences::class, inversedBy: 'inscrits')]
    private Collection $competences;

    #[ORM\ManyToMany(targetEntity: session::class, inversedBy: 'inscrits')]
    private Collection $Session;

    #[ORM\ManyToOne(inversedBy: 'inscrit')]
    #[ORM\JoinColumn(nullable: false)]
    

    public function __construct()
    {
        $this->metier = new ArrayCollection();
        $this->emprunt = new ArrayCollection();
        $this->competences = new ArrayCollection();
        $this->Session = new ArrayCollection();
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


    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;


        return $this;
    }

    public function getMetier(): Collection
    {
        return $this->metier;
    }

    public function addMetier(metier $metier): self
    {
        if (!$this->metier->contains($metier)) {
            $this->metier->add($metier);
        }

        return $this;
    }

    public function removeMetier(metier $metier): self
    {
        $this->metier->removeElement($metier);

        return $this;

    }

    /**
     * @return Collection<int, Emprunt>
     */
    public function getEmprunt(): Collection
    {
        return $this->emprunt;
    }

    public function addEmprunt(Emprunt $emprunt): self
    {
        if (!$this->emprunt->contains($emprunt)) {
            $this->emprunt->add($emprunt);
            $emprunt->setInscrit($this);
        }

        return $this;
    }

    public function removeEmprunt(Emprunt $emprunt): self
    {
        if ($this->emprunt->removeElement($emprunt)) {
            // set the owning side to null (unless already changed)
            if ($emprunt->getInscrit() === $this) {
                $emprunt->setInscrit(null);
            }
        }

        return $this;
    }

    public function getPaiement(): ?typePaiement
    {
        return $this->Paiement;
    }

    public function setPaiement(?typePaiement $Paiement): self
    {
        $this->Paiement = $Paiement;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPremierKabaret(): ?int
    {
        return $this->premierKabaret;
    }

    public function setPremierKabaret(?int $premierKabaret): self
    {
        $this->premierKabaret = $premierKabaret;

        return $this;
    }

    public function getMatosDispo(): ?string
    {
        return $this->matosDispo;
    }

    public function setMatosDispo(?string $matosDispo): self
    {
        $this->matosDispo = $matosDispo;

        return $this;
    }

    public function getPermis(): ?int
    {
        return $this->permis;
    }

    public function setPermis(?int $permis): self
    {
        $this->permis = $permis;

        return $this;
    }

    public function getVehicule(): ?int
    {
        return $this->vehicule;
    }

    public function setVehicule(?int $vehicule): self
    {
        $this->vehicule = $vehicule;

        return $this;
    }

    public function getLogementId(): ?logement
    {
        return $this->logementId;
    }

    public function setLogementId(?logement $logementId): self
    {
        $this->logementId = $logementId;

        return $this;
    }

    /**
     * @return Collection<int, typeCompetences>
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(typeCompetences $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences->add($competence);
        }

        return $this;
    }

    public function removeCompetence(typeCompetences $competence): self
    {
        $this->competences->removeElement($competence);

        return $this;
    }

    /**
     * @return Collection<int, session>
     */
    public function getSession(): Collection
    {
        return $this->Session;
    }

    public function addSession(session $session): self
    {
        if (!$this->Session->contains($session)) {
            $this->Session->add($session);
        }

        return $this;
    }

    public function removeSession(session $session): self
    {
        $this->Session->removeElement($session);

        return $this;
    }
}