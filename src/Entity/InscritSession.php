<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use App\Entity\Session;
use App\Entity\Inscrit;

/**
 * @ORM\Entity
 * @ORM\Table(name="inscription_session")
 */
class InscriptionSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Inscrit::class, inversedBy: 'inscriptionSessions')]
    #[ORM\JoinColumn(name: 'inscrit_id', referencedColumnName: 'id')]
    private ?Inscrit $inscrit = null;

    #[ORM\ManyToOne(targetEntity: Session::class, inversedBy: 'inscriptionSessions')]
    #[ORM\JoinColumn(name: 'session_id', referencedColumnName: 'id')]
    private ?Session $session = null;

    #[ORM\Column(nullable: true)]
    private ?int $porteurProjet = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function getPorteurProjet(): ?int
    {
        return $this->porteurProjet;
    }

    public function setPorteurProjet(?int $porteurProjet): self
    {
        $this->porteurProjet = $porteurProjet;

        return $this;
    }
}
