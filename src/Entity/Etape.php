<?php

namespace App\Entity;

use App\Repository\EtapeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtapeRepository::class)
 */
class Etape
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
    private $nom;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateHeureDebut;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateHeureCreation;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $tarif;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbInscriptionsMax;

    /**
     * @ORM\OneToMany(targetEntity=InscriptionEtape::class, mappedBy="etape")
     */
    private $inscriptionEtapes;

    /**
     * @ORM\ManyToOne(targetEntity=Evenement::class, inversedBy="etapes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $evenement;

    /**
     * @ORM\ManyToOne(targetEntity=Arme::class, inversedBy="etapes")
     */
    private $arme;

    public function __construct()
    {
        $this->inscriptionEtapes = new ArrayCollection();
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

    public function getDateHeureCreation(): ?\DateTimeInterface
    {
        return $this->dateHeureCreation;
    }

    public function setDateHeureCreation(\DateTimeInterface $dateHeureCreation): self
    {
        $this->dateHeureCreation = $dateHeureCreation;

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

    public function getNbInscriptionsMax(): ?int
    {
        return $this->nbInscriptionsMax;
    }

    public function setNbInscriptionsMax(?int $nbInscriptionsMax): self
    {
        $this->nbInscriptionsMax = $nbInscriptionsMax;

        return $this;
    }

    /**
     * @return Collection|InscriptionEtape[]
     */
    public function getInscriptionEtapes(): Collection
    {
        return $this->inscriptionEtapes;
    }

    public function addInscriptionEtape(InscriptionEtape $inscriptionEtape): self
    {
        if (!$this->inscriptionEtapes->contains($inscriptionEtape)) {
            $this->inscriptionEtapes[] = $inscriptionEtape;
            $inscriptionEtape->setEtape($this);
        }

        return $this;
    }

    public function removeInscriptionEtape(InscriptionEtape $inscriptionEtape): self
    {
        if ($this->inscriptionEtapes->removeElement($inscriptionEtape)) {
            // set the owning side to null (unless already changed)
            if ($inscriptionEtape->getEtape() === $this) {
                $inscriptionEtape->setEtape(null);
            }
        }

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

    public function getArme(): ?Arme
    {
        return $this->arme;
    }

    public function setArme(?Arme $arme): self
    {
        $this->arme = $arme;

        return $this;
    }
}
