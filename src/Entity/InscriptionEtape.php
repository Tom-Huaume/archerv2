<?php

namespace App\Entity;

use App\Repository\InscriptionEtapeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InscriptionEtapeRepository::class)
 */
class InscriptionEtape
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $validation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateHeureInscription;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\ManyToOne(targetEntity=Membre::class, inversedBy="inscriptionEtapes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $membre;

    /**
     * @ORM\ManyToOne(targetEntity=Etape::class, inversedBy="inscriptionEtapes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etape;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $validateur;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $arme;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateHeureValidation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValidation(): ?bool
    {
        return $this->validation;
    }

    public function setValidation(bool $validation): self
    {
        $this->validation = $validation;

        return $this;
    }

    public function getDateHeureInscription(): ?\DateTimeInterface
    {
        return $this->dateHeureInscription;
    }

    public function setDateHeureInscription(\DateTimeInterface $dateHeureInscription): self
    {
        $this->dateHeureInscription = $dateHeureInscription;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getMembre(): ?Membre
    {
        return $this->membre;
    }

    public function setMembre(?Membre $membre): self
    {
        $this->membre = $membre;

        return $this;
    }

    public function getEtape(): ?Etape
    {
        return $this->etape;
    }

    public function setEtape(?Etape $etape): self
    {
        $this->etape = $etape;

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

    public function getArme(): ?string
    {
        return $this->arme;
    }

    public function setArme(?string $arme): self
    {
        $this->arme = $arme;

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
