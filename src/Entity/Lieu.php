<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity(fields={"rue", "codePostal", "ville"}, message="Un lieu avec la même adresse/ville existe déjà !")
 * @ORM\Entity(repositoryClass=LieuRepository::class)
 */
class Lieu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $nom;

    /**
     * @Assert\NotBlank(message="Vous devez saisir un nom de rue")
     * @ORM\Column(type="string", length=50)
     */
    private $rue;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $rue2;

    /**
     * @Assert\NotBlank(message="Vous devez saisir un code postal")
     * @ORM\Column(type="string", length=5)
     */
    private $codePostal;

    /**
     * @Assert\NotBlank(message="Vous devez saisir une ville")
     * @ORM\Column(type="string", length=50)
     */
    private $ville;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;

    /**
     * @ORM\Column(type="boolean")
     */
    private $club;

    /**
     * @ORM\Column(type="boolean")
     */
    private $list;

    /**
     * @ORM\OneToMany(targetEntity=Trajet::class, mappedBy="lieuDepart")
     */
    private $trajets;

    /**
     * @ORM\OneToMany(targetEntity=Evenement::class, mappedBy="lieuDestination")
     */
    private $evenements;

    public function __construct()
    {
        $this->trajets = new ArrayCollection();
        $this->evenements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): self
    {
        $this->rue = $rue;

        return $this;
    }

    public function getRue2(): ?string
    {
        return $this->rue2;
    }

    public function setRue2(?string $rue2): self
    {
        $this->rue2 = $rue2;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getClub(): ?bool
    {
        return $this->club;
    }

    public function setClub(bool $club): self
    {
        $this->club = $club;

        return $this;
    }

    public function getList(): ?bool
    {
        return $this->list;
    }

    public function setList(bool $list): self
    {
        $this->list = $list;

        return $this;
    }

    /**
     * @return Collection|Trajet[]
     */
    public function getTrajets(): Collection
    {
        return $this->trajets;
    }

    public function addTrajet(Trajet $trajet): self
    {
        if (!$this->trajets->contains($trajet)) {
            $this->trajets[] = $trajet;
            $trajet->setLieuDepart($this);
        }

        return $this;
    }

    public function removeTrajet(Trajet $trajet): self
    {
        if ($this->trajets->removeElement($trajet)) {
            // set the owning side to null (unless already changed)
            if ($trajet->getLieuDepart() === $this) {
                $trajet->setLieuDepart(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Evenement[]
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): self
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements[] = $evenement;
            $evenement->setLieuDestination($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        if ($this->evenements->removeElement($evenement)) {
            // set the owning side to null (unless already changed)
            if ($evenement->getLieuDestination() === $this) {
                $evenement->setLieuDestination(null);
            }
        }

        return $this;
    }
}
