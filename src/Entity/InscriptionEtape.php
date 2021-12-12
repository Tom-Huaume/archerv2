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
}
