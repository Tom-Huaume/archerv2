<?php

namespace App\Entity;

use App\Repository\MembreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @UniqueEntity(fields={"numLicence"}, message="Ce numéro de licence existe déjà !")
 * @ORM\Entity(repositoryClass=MembreRepository::class)
 */
class Membre implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=10, nullable=true, unique=true)
     */
    private $numLicence;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateNaissance;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $telMobile;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $lateralite;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $statutLicence;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $categorieAge;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $typeLicence;

    /**
     * @ORM\OneToMany(targetEntity=Trajet::class, mappedBy="organisateur")
     */
    private $trajets;

    /**
     * @ORM\OneToMany(targetEntity=ReservationTrajet::class, mappedBy="membre")
     */
    private $reservationTrajets;

    /**
     * @ORM\OneToMany(targetEntity=InscriptionEtape::class, mappedBy="membre")
     */
    private $inscriptionEtapes;

    public function __construct()
    {
        $this->trajets = new ArrayCollection();
        $this->reservationTrajets = new ArrayCollection();
        $this->inscriptionEtapes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {

        return $this->roles;

//        $roles = $this->roles;
//        // guarantee every user at least has ROLE_USER
//        $roles[] = 'ROLE_USER';
//
//        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNumLicence(): ?string
    {
        return $this->numLicence;
    }

    public function setNumLicence(?string $numLicence): self
    {
        $this->numLicence = $numLicence;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getTelMobile(): ?string
    {
        return $this->telMobile;
    }

    public function setTelMobile(?string $telMobile): self
    {
        $this->telMobile = $telMobile;

        return $this;
    }

    public function getLateralite(): ?string
    {
        return $this->lateralite;
    }

    public function setLateralite(?string $lateralite): self
    {
        $this->lateralite = $lateralite;

        return $this;
    }

    public function getStatutLicence(): ?bool
    {
        return $this->statutLicence;
    }

    public function setStatutLicence(?bool $statutLicence): self
    {
        $this->statutLicence = $statutLicence;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getCategorieAge(): ?string
    {
        return $this->categorieAge;
    }

    public function setCategorieAge(string $categorieAge): self
    {
        $this->categorieAge = $categorieAge;

        return $this;
    }

    public function getTypeLicence(): ?string
    {
        return $this->typeLicence;
    }

    public function setTypeLicence(?string $typeLicence): self
    {
        $this->typeLicence = $typeLicence;

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
            $trajet->setOrganisateur($this);
        }

        return $this;
    }

    public function removeTrajet(Trajet $trajet): self
    {
        if ($this->trajets->removeElement($trajet)) {
            // set the owning side to null (unless already changed)
            if ($trajet->getOrganisateur() === $this) {
                $trajet->setOrganisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ReservationTrajet[]
     */
    public function getReservationTrajets(): Collection
    {
        return $this->reservationTrajets;
    }

    public function addReservationTrajet(ReservationTrajet $reservationTrajet): self
    {
        if (!$this->reservationTrajets->contains($reservationTrajet)) {
            $this->reservationTrajets[] = $reservationTrajet;
            $reservationTrajet->setMembre($this);
        }

        return $this;
    }

    public function removeReservationTrajet(ReservationTrajet $reservationTrajet): self
    {
        if ($this->reservationTrajets->removeElement($reservationTrajet)) {
            // set the owning side to null (unless already changed)
            if ($reservationTrajet->getMembre() === $this) {
                $reservationTrajet->setMembre(null);
            }
        }

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
            $inscriptionEtape->setMembre($this);
        }

        return $this;
    }

    public function removeInscriptionEtape(InscriptionEtape $inscriptionEtape): self
    {
        if ($this->inscriptionEtapes->removeElement($inscriptionEtape)) {
            // set the owning side to null (unless already changed)
            if ($inscriptionEtape->getMembre() === $this) {
                $inscriptionEtape->setMembre(null);
            }
        }

        return $this;
    }
}
