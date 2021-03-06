<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EvenementRepository::class)
 */
class Evenement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Length(max=50)
     * @Assert\NotBlank(message="Vous devez donner un nom à l'évènement")
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @Assert\GreaterThanOrEqual("today UTC", message="L'évènement doit se dérouler à une date future")
     * @Assert\NotBlank(message="Vous devez préciser la date/heure de début de l'évènement")
     * @ORM\Column(type="datetime")
     */
    private $dateHeureDebut;

    /**
     * @Assert\LessThanOrEqual(propertyPath="dateHeureDebut", message="Doit être antérieure au début de l'évènement")
     * @Assert\NotBlank(message="Vous devez fixer la date/heure limite pour l'inscription")
     * @ORM\Column(type="datetime")
     */
    private $dateHeureLimiteInscription;

    /**
     * @Assert\Length(min=1)
     * @Assert\Positive(message="Merci de renseigner une nombre supérieur à zéro")
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbInscriptionsMax;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $etat;

    /**
     * @Assert\Length(max=100)
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $tarif;

    /**
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateHeureCreation;

    /**
     * @ORM\OneToMany(targetEntity=Trajet::class, mappedBy="evenement")
     */
    private $trajets;

    /**
     * @ORM\ManyToOne(targetEntity=Lieu::class, inversedBy="evenements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lieuDestination;

    /**
     * @ORM\OneToMany(targetEntity=Etape::class, mappedBy="evenement")
     */
    private $etapes;

    /**
     * @Assert\GreaterThan(propertyPath="dateHeureDebut", message="L'évènement doit se terminer après la date/heure de début")
     * @Assert\NotBlank(message="Vous devez préciser la date/heure de fin de l'évènement")
     * @ORM\Column(type="datetime")
     */
    private $dateHeureFin;

    public function __construct()
    {
        $this->trajets = new ArrayCollection();
        $this->etapes = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): self
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDateHeureLimiteInscription(): ?\DateTimeInterface
    {
        return $this->dateHeureLimiteInscription;
    }

    public function setDateHeureLimiteInscription(\DateTimeInterface $dateHeureLimiteInscription): self
    {
        $this->dateHeureLimiteInscription = $dateHeureLimiteInscription;

        return $this;
    }

    public function getNbInscriptionsMax(): ?int
    {
        return $this->nbInscriptionsMax;
    }

    public function setNbInscriptionsMax(?int $nbInscriptionsMax): self
    {
        $this->nbInscriptionsMax = $nbInscriptionsMax;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getTarif(): ?string
    {
        return $this->tarif;
    }

    public function setTarif(?string $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

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
            $trajet->setEvenement($this);
        }

        return $this;
    }

    public function removeTrajet(Trajet $trajet): self
    {
        if ($this->trajets->removeElement($trajet)) {
            // set the owning side to null (unless already changed)
            if ($trajet->getEvenement() === $this) {
                $trajet->setEvenement(null);
            }
        }

        return $this;
    }

    public function getLieuDestination(): ?Lieu
    {
        return $this->lieuDestination;
    }

    public function setLieuDestination(?Lieu $lieuDestination): self
    {
        $this->lieuDestination = $lieuDestination;

        return $this;
    }

    /**
     * @return Collection|Etape[]
     */
    public function getEtapes(): Collection
    {
        return $this->etapes;
    }

    public function addEtape(Etape $etape): self
    {
        if (!$this->etapes->contains($etape)) {
            $this->etapes[] = $etape;
            $etape->setEvenement($this);
        }

        return $this;
    }

    public function removeEtape(Etape $etape): self
    {
        if ($this->etapes->removeElement($etape)) {
            // set the owning side to null (unless already changed)
            if ($etape->getEvenement() === $this) {
                $etape->setEvenement(null);
            }
        }

        return $this;
    }

    public function getDateHeureFin(): ?\DateTimeInterface
    {
        return $this->dateHeureFin;
    }

    public function setDateHeureFin(\DateTimeInterface $dateHeureFin): self
    {
        $this->dateHeureFin = $dateHeureFin;

        return $this;
    }
}
