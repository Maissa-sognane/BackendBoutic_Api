<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\InscriptionNewsLetterController;
use App\Repository\NewsLetterRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NewsLetterRepository::class)]
#[ApiResource(
	collectionOperations: [
		'get'=>[
			'path'=>'/newsletter'
		],
		'post'=>[
			'path'=>'/newsletter/inscription',
			'write'=>false,
			'controller'=>InscriptionNewsLetterController::class,
			'deserialize'=>false,
		]
	],
	itemOperations: [
		'get'=>[
			'path'=>'/newsletter/{id}'
		],
		'delete'=>[
			'path'=>'/newsletter/{id}'
		]
	],
	denormalizationContext: ['groups'=>['write:newsletter']],
	normalizationContext: ['groups'=>['read:newsletter']]
)]
#[UniqueEntity(
	fields: ['email'],
	message: 'Email existe deja.',
	errorPath: 'port',
)]
class NewsLetter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
	#[Groups(['read:newsletter'])]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
	#[Groups(['write:newsletter', 'read:newsletter'])]
	#[Assert\NotBlank(message: 'Champ email  vide')]
	#[Assert\Email(message: 'Email invalide')]

    private ?string $email = null;

    #[ORM\Column]
	#[Groups(['write:newsletter', 'read:newsletter'])]
    private ?bool $isDeleted = null;

    #[ORM\Column]
	#[Groups(['write:newsletter', 'read:newsletter'])]
    private ?\DateTimeImmutable $createdAt = null;

	public function __construct()
	{
		$this->isDeleted = false;
		$this->createdAt = new \DateTimeImmutable();
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
}
