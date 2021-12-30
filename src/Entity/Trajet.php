<?php

namespace App\Entity;

use App\Repository\TrajetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TrajetRepository::class)
 */
class Trajet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateHeureDepart;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbPlaces;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $typeVoiture;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $couleurVoiture;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateHeureCreation;

    /**
     * @ORM\ManyToOne(targetEntity=Lieu::class, inversedBy="trajets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lieuDepart;

    /**
     * @ORM\ManyToOne(targetEntity=Evenement::class, inversedBy="trajets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $evenement;

    /**
     * @ORM\ManyToOne(targetEntity=Membre::class, inversedBy="trajets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisateur;

    /**
     * @ORM\OneToMany(targetEntity=ReservationTrajet::class, mappedBy="trajet")
     */
    private $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateHeureDepart(): ?\DateTimeInterface
    {
        return $this->dateHeureDepart;
    }

    public function setDateHeureDepart(\DateTimeInterface $dateHeureDepart): self
    {
        $this->dateHeureDepart = $dateHeureDepart;

        return $this;
    }

    public function getNbPlaces(): ?int
    {
        return $this->nbPlaces;
    }

    public function setNbPlaces(int $nbPlaces): self
    {
        $this->nbPlaces = $nbPlaces;

        return $this;
    }

    public function getTypeVoiture(): ?string
    {
        return $this->typeVoiture;
    }

    public function setTypeVoiture(?string $typeVoiture): self
    {
        $this->typeVoiture = $typeVoiture;

        return $this;
    }

    public function getCouleurVoiture(): ?string
    {
        return $this->couleurVoiture;
    }

    public function setCouleurVoiture(?string $couleurVoiture): self
    {
        $this->couleurVoiture = $couleurVoiture;

        return $this;
    }

    public function getDateHeureCreation(): ?\DateTimeInterface
    {
        return $this->dateHeureCreation;
    }

    public function setDateHeureCreation(\DateTimeInterface $dateHeureCreation): self
    {
        $this->dateHeureCreation = $dateHeureCreation;

        return $this;
    }

    public function getLieuDepart(): ?Lieu
    {
        return $this->lieuDepart;
    }

    public function setLieuDepart(?Lieu $lieuDepart): self
    {
        $this->lieuDepart = $lieuDepart;

        return $this;
    }

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): self
    {
        $this->evenement = $evenement;

        return $this;
    }

    public function getOrganisateur(): ?Membre
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?Membre $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    /**
     * @return Collection|ReservationTrajet[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(ReservationTrajet $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setTrajet($this);
        }

        return $this;
    }

    public function removeReservation(ReservationTrajet $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getTrajet() === $this) {
                $reservation->setTrajet(null);
            }
        }

        return $this;
    }
}
