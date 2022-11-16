<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\DemandeController;
use App\Repository\DemandesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DemandesRepository::class)]
#[ApiResource(
	collectionOperations: [
		'get',
		'post'=>[
			'controller'=>DemandeController::class,
			'write'=>false,
			'deserialize'=>false
		]
	],
	itemOperations: [
		'get',
		'put',
		'delete'
],
	denormalizationContext: ['groups'=>['write:demande']],
	normalizationContext: ['groups'=>['read:demande']]
)]
class Demandes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
	#[Groups(['read:demande'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
	#[Groups(['read:demande', 'write:demande'])]
	#[Assert\NotBlank(message: 'Champ prenom vide')]
	#[Assert\Length(min: 1, max: 100,
		minMessage: 'Le prenom  doit depasser 1 caracteres minimum',
		maxMessage: 'Le prenom ne doit pas depasser 100 caracteres minimum'
	)]
    private ?string $prenom = null;

    #[ORM\Column(length: 100)]
	#[Assert\NotBlank(message: 'Champ nom vide')]
	#[Assert\Length(min: 1, max: 100,
		minMessage: 'Le nom  doit depasser 1 caracteres minimum',
		maxMessage: 'Le nom ne doit pas depasser 100 caracteres minimum'
	)]
	#[Groups(['read:demande', 'write:demande'])]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
	#[Assert\NotBlank(message: 'Champ representant vide')]
	#[Assert\Length(min: 5, max: 100,
		minMessage: 'Le representant  doit depasser 5 caracteres minimum',
		maxMessage: 'Le representant ne doit pas depasser 100 caracteres minimum'
	)]
	#[Groups(['read:demande', 'write:demande'])]
    private ?string $representant = null;

    #[ORM\Column(length: 100)]
	#[Assert\NotBlank(message: 'Champ email du representant vide')]
	#[Assert\Email(message: 'email non valide')]
	#[Assert\Length(min: 10, max: 100,
		minMessage: 'L\'adresse email  doit depasser 10 caracteres minimum',
		maxMessage: 'L\'adresse email ne doit pas depasser 100 caracteres minimum'
	)]
	#[Groups(['read:demande', 'write:demande'])]
    private ?string $emailRepresentant = null;

    #[ORM\Column(length: 100)]
	#[Groups(['read:demande', 'write:demande'])]
	#[Assert\NotBlank(message: 'Champ numero du representant vide')]
	#[Assert\Length(min: 5, max: 100,
		minMessage: 'Le numero du representant   doit depasser 100 caracteres minimum',
		maxMessage: 'Le numero du representant  ne doit pas depasser 100 caracteres minimum'
	)]
    private ?string $numeroTelephoneRepresentant = null;

    #[ORM\Column(length: 100)]
	#[Groups(['read:demande', 'write:demande'])]
	#[Assert\NotBlank(message: 'Champ numero du entreprise vide')]
	#[Assert\Length(min: 5, max: 100,
		minMessage: 'Le numero du entreprise   doit depasser 10 caracteres minimum',
		maxMessage: 'Le numero du entreprise  ne doit pas depasser 100 caracteres minimum'
	)]
    private ?string $numeroTelephoneEntreprise = null;

    #[ORM\Column(length: 100)]
	#[Assert\NotBlank(message: 'Champ adresse du entreprise vide')]
	#[Assert\Length(min: 5, max: 100,
		minMessage: 'L\' adresse doit depasser 5 caracteres minimum',
		maxMessage: 'L\' adresse ne doit pas depasser 100 caracteres minimum'
	)]
	#[Groups(['read:demande', 'write:demande'])]
    private ?string $adresseEntreprise = null;

    #[ORM\Column]
	#[Groups(['read:demande', 'write:demande'])]
	#[Assert\NotBlank(message: 'Champ nombre employee  vide')]
	#[Assert\Length(min: 2, max: 10000,
		minMessage: 'Le nombre employee doit depasser 2 caracteres minimum',
		maxMessage: 'Le nombre employee ne doit pas depasser 10000 caracteres minimum'
	)]
    private ?int $nombreEmployee = null;

    #[ORM\Column]
	#[Groups(['read:demande', 'write:demande'])]
    private ?bool $isDeleted = null;

    #[ORM\Column]
	#[Groups(['read:demande', 'write:demande'])]
    private ?\DateTimeImmutable $cretedAt = null;

    #[ORM\ManyToMany(targetEntity: TypeMachine::class, inversedBy: 'demandes')]
	#[Assert\NotBlank(message: 'Champ type de machines vide')]
	#[Groups(['read:demande', 'write:demande'])]
    private Collection $machines;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
	#[Assert\NotBlank(message: 'Champ statut vide')]
	#[Groups(['read:demande', 'write:demande'])]
    private ?Statut $statut = null;

	public function __construct()
	{
		$this->isDeleted = false;
		$this->cretedAt = new \DateTimeImmutable();
		$this->machines = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
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

    public function getRepresentant(): ?string
    {
        return $this->representant;
    }

    public function setRepresentant(string $representant): self
    {
        $this->representant = $representant;

        return $this;
    }

    public function getEmailRepresentant(): ?string
    {
        return $this->emailRepresentant;
    }

    public function setEmailRepresentant(string $emailRepresentant): self
    {
        $this->emailRepresentant = $emailRepresentant;

        return $this;
    }

    public function getNumeroTelephoneRepresentant(): ?string
    {
        return $this->numeroTelephoneRepresentant;
    }

    public function setNumeroTelephoneRepresentant(string $numeroTelephoneRepresentant): self
    {
        $this->numeroTelephoneRepresentant = $numeroTelephoneRepresentant;

        return $this;
    }

    public function getNumeroTelephoneEntreprise(): ?string
    {
        return $this->numeroTelephoneEntreprise;
    }

    public function setNumeroTelephoneEntreprise(string $numeroTelephoneEntreprise): self
    {
        $this->numeroTelephoneEntreprise = $numeroTelephoneEntreprise;

        return $this;
    }

    public function getAdresseEntreprise(): ?string
    {
        return $this->adresseEntreprise;
    }

    public function setAdresseEntreprise(string $adresseEntreprise): self
    {
        $this->adresseEntreprise = $adresseEntreprise;

        return $this;
    }

    public function getNombreEmployee(): ?int
    {
        return $this->nombreEmployee;
    }

    public function setNombreEmployee(int $nombreEmployee): self
    {
        $this->nombreEmployee = $nombreEmployee;

        return $this;
    }

    public function isIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getCretedAt(): ?\DateTimeImmutable
    {
        return $this->cretedAt;
    }

    public function setCretedAt(\DateTimeImmutable $cretedAt): self
    {
        $this->cretedAt = $cretedAt;

        return $this;
    }

    /**
     * @return Collection<int, TypeMachine>
     */
    public function getMachines(): Collection
    {
        return $this->machines;
    }

    public function addMachine(TypeMachine $machine): self
    {
        if (!$this->machines->contains($machine)) {
            $this->machines->add($machine);
        }

        return $this;
    }

    public function removeMachine(TypeMachine $machine): self
    {
        $this->machines->removeElement($machine);

        return $this;
    }

    public function getStatut(): ?Statut
    {
        return $this->statut;
    }

    public function setStatut(?Statut $statut): self
    {
        $this->statut = $statut;

        return $this;
    }
}
