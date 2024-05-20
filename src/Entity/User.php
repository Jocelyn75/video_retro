<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Cette adresse mail est déjà associée à un compte')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_naiss = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adr_user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $complement_adr = null;

    #[ORM\Column(nullable: true)]
    private ?int $code_postal = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ville = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tel_user = null;

    #[ORM\Column(nullable: true)]
    private ?float $cagnotte = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Commandes::class)]
    private Collection $commandes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $activation = null;

    #[ORM\OneToOne(inversedBy: 'user', cascade: ['persist', 'remove'])]
    private ?AdrFacturationUser $adr_facturation_user = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: AdrLivraisonUser::class)]
    private Collection $adr_livraison_user;

    public function __construct()
    {
        $this->adr_livraison_user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
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

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaiss(): ?\DateTimeInterface
    {
        return $this->date_naiss;
    }

    public function setDateNaiss(?\DateTimeInterface $date_naiss): static
    {
        $this->date_naiss = $date_naiss;

        return $this;
    }

    public function getAdrUser(): ?string
    {
        return $this->adr_user;
    }

    public function setAdrUser(?string $adr_user): static
    {
        $this->adr_user = $adr_user;

        return $this;
    }

    public function getComplementAdr(): ?string
    {
        return $this->complement_adr;
    }

    public function setComplementAdr(?string $complement_adr): static
    {
        $this->complement_adr = $complement_adr;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->code_postal;
    }

    public function setCodePostal(?int $code_postal): static
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getTelUser(): ?string
    {
        return $this->tel_user;
    }

    public function setTelUser(?string $tel_user): static
    {
        $this->tel_user = $tel_user;

        return $this;
    }

    public function getCagnotte(): ?float
    {
        return $this->cagnotte;
    }

    public function setCagnotte(?float $cagnotte): static
    {
        $this->cagnotte = $cagnotte;

        return $this;
    }

    /**
     * @return Collection<int, Commandes>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commandes $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setUser($this);
        }

        return $this;
    }

    public function removeCommande(Commandes $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getUser() === $this) {
                $commande->setUser(null);
            }
        }

        return $this;
    }

    public function getActivation(): ?string
    {
        return $this->activation;
    }

    public function setActivation(?string $activation): static
    {
        $this->activation = $activation;

        return $this;
    }

    public function getAdrFacturationUser(): ?AdrFacturationUser
    {
        return $this->adr_facturation_user;
    }

    public function setAdrFacturationUser(?AdrFacturationUser $adr_facturation_user): static
    {
        $this->adr_facturation_user = $adr_facturation_user;

        return $this;
    }

    /**
     * @return Collection<int, AdrLivraisonUser>
     */
    public function getAdrLivraisonUser(): Collection
    {
        return $this->adr_livraison_user;
    }

    public function addAdrLivraisonUser(AdrLivraisonUser $adrLivraisonUser): static
    {
        if (!$this->adr_livraison_user->contains($adrLivraisonUser)) {
            $this->adr_livraison_user->add($adrLivraisonUser);
            $adrLivraisonUser->setUser($this);
        }

        return $this;
    }

    public function removeAdrLivraisonUser(AdrLivraisonUser $adrLivraisonUser): static
    {
        if ($this->adr_livraison_user->removeElement($adrLivraisonUser)) {
            // set the owning side to null (unless already changed)
            if ($adrLivraisonUser->getUser() === $this) {
                $adrLivraisonUser->setUser(null);
            }
        }

        return $this;
    }
}
