<?php

namespace App\Entity;

use App\Repository\EtapeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\Length(max=50)
     * @Assert\NotBlank(message="Vous devez donner un nom à l'étape")
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @Assert\NotBlank(message="Vous devez préciser la date/heure de début de l'étape")
     * @ORM\Column(type="datetime")
     */
    private $dateHeureDebut;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateHeureCreation;

    /**
     * @Assert\Length(max=100)
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $tarif;

    /**
     * @Assert\Length(min=1)
     * @Assert\Positive(message="Merci de renseigner une nombre supérieur à zéro")
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
     * @Assert\NotNull(message="Vous devez sélectionner au moins une arme")
     * @ORM\ManyToMany(targetEntity=Arme::class, inversedBy="etapes")
     */
    private $armes;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $validateur;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateHeureValidation;


    public function __construct()
    {
        $this->inscriptionEtapes = new ArrayCollection();
        $this->armes = new ArrayCollection();
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

    /**
     * @return Collection|Arme[]
     */
    public function getArmes(): Collection
    {
        return $this->armes;
    }

    public function addArme(Arme $arme): self
    {
        if (!$this->armes->contains($arme)) {
            $this->armes[] = $arme;
        }

        return $this;
    }

    public function removeArme(Arme $arme): self
    {
        $this->armes->removeElement($arme);

        return $this;
    }

    public function getValidateur(): ?string
    {
        return $this->validateur;
    }

    public function setValidateur(?string $validateur): self
    {
        $this->validateur = $validateur;

        return $this;
    }

    public function getDateHeureValidation(): ?\DateTimeInterface
    {
        return $this->dateHeureValidation;
    }

    public function setDateHeureValidation(?\DateTimeInterface $dateHeureValidation): self
    {
        $this->dateHeureValidation = $dateHeureValidation;

        return $this;
    }

}
