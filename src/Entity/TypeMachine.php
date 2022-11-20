<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TypeMachineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TypeMachineRepository::class)]
#[ApiResource(
	collectionOperations: [
                     		'get'=>[
                     			'path'=>'/machines'
                     		],
                     		'post'=>[
                     			'path'=>'/machines'
                     		]
                     	],
	itemOperations: [
                     		'get'=>[
                     			'path'=>'/machines/{id}'
                     		],
                     		'put'=>[
                     			'path'=>'/machines/{id}'
                     		],
                     		'delete'=>[
                     			'path'=>'/machines/{id}'
                     		]
                     	],
	denormalizationContext: ['groups'=>['write:machine']],
	normalizationContext: ['groups'=>['read:machine']]

)]
class TypeMachine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
	#[Groups(['read:machine'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
	#[Groups(['read:machine', 'write:machine'])]
    private ?string $libelle = null;

    #[ORM\Column]
	#[Groups(['read:machine', 'write:machine'])]
    private ?bool $isDeleted = null;

    #[ORM\Column]
	#[Groups(['read:machine', 'write:machine'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToMany(targetEntity: Demandes::class, mappedBy: 'machines')]
    private Collection $demandes;

	public function __construct()
                     	{
                     		$this->isDeleted = false;
                     		$this->createdAt = new \DateTimeImmutable();
                       $this->demandes = new ArrayCollection();
                     	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Demandes>
     */
    public function getDemandes(): Collection
    {
        return $this->demandes;
    }

    public function addDemande(Demandes $demande): self
    {
        if (!$this->demandes->contains($demande)) {
            $this->demandes->add($demande);
            $demande->addMachine($this);
        }

        return $this;
    }

    public function removeDemande(Demandes $demande): self
    {
        if ($this->demandes->removeElement($demande)) {
            $demande->removeMachine($this);
        }

        return $this;
    }
}
