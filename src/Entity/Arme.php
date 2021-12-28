<?php

namespace App\Entity;

use App\Repository\ArmeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity(fields={"sigle", "designation"}, message="Ce couple sigle/désignation existe déjà !")
 * @UniqueEntity(fields={"sigle"}, message="Ce sigle existe déjà !")
 * @UniqueEntity(fields={"designation"}, message="Cette désignation existe déjà !")
 * @ORM\Entity(repositoryClass=ArmeRepository::class)
 */
class Arme
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Length(max=10)
     * @ORM\Column(type="string", length=10, unique=true, nullable=true)
     */
    private $sigle;

    /**
     * @Assert\NotBlank(message="Vous devez saisir une désignation")
     * @Assert\Length(max=50)
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $designation;

    /**
     * @ORM\ManyToMany(targetEntity=Etape::class, mappedBy="armes")
     */
    private $etapes;

    public function __construct()
    {
        $this->etapes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSigle(): ?string
    {
        return $this->sigle;
    }

    public function setSigle(?string $sigle): self
    {
        $this->sigle = $sigle;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

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
            $etape->addArme($this);
        }

        return $this;
    }

    public function removeEtape(Etape $etape): self
    {
        if ($this->etapes->removeElement($etape)) {
            $etape->removeArme($this);
        }

        return $this;
    }
}
