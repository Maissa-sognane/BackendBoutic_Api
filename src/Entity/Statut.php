<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\StatutRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StatutRepository::class)]
#[ApiResource(
	collectionOperations: [
                     		'get',
                     		'post'
                     	],
	itemOperations: [
                     		'get',
                     		'put',
                     		'delete'
                     	],
	denormalizationContext: ['groups'=>'write:statut'],
	normalizationContext: ['groups'=>'read:statut']
)]
class Statut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
	#[Groups(['read:statut'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
	#[Groups(['write:statut', 'read:statut'])]
    private ?string $libelle = null;

    #[ORM\Column]
	#[Groups(['write:statut', 'read:statut'])]
    private ?bool $isDeleted = null;

    #[ORM\OneToMany(mappedBy: 'statut', targetEntity: Demandes::class)]
    private Collection $demandes;

	public function __construct()
                     	{
                     		$this->isDeleted = false;
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
            $demande->setStatut($this);
        }

        return $this;
    }

    public function removeDemande(Demandes $demande): self
    {
        if ($this->demandes->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getStatut() === $this) {
                $demande->setStatut(null);
            }
        }

        return $this;
    }
}
