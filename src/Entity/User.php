<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\CreateUsersController;
use App\Controller\UpdateAvatarController;
use App\Controller\UpdatePasswordUserController;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
	collectionOperations: [
		'get'=>[
			'path'=>'/utilisateurs'
		],
		'post'=>[
			'path'=>'/utilisateur/creation',
			'deserialize'=>false,
			'write'=>false,
			'controller'=>CreateUsersController::class
		]
	],
	itemOperations:[
		'get'=>[
			'path'=>'/utilisateur/{id}',
		],
		'put' => [
			'path'=>'/utilisateur/{id}/edition',
			'denormalization_context'=>['groups'=>['put:itemUser']]
		],
		'changer-avatar'=>[
			'method'=>'post',
			'path'=>'/utilisateur/{id}/image',
			'deserialize'=>false,
			'controller'=>UpdateAvatarController::class,
			'denormalization_context'=>[ 'groups'=>['put:avatar']]
		],
		'changer-password'=>[
			'method'=>'put',
			'path'=>'/utilisateur/{id}/password-reset',
			'controller'=>UpdatePasswordUserController::class,
			'write'=>false,
			'deserialize'=>false,
			'denormalization_context'=>[ 'groups'=>['put:password']]

		],
		'delete'=>[
			'path'=>'/utilisateur/{id}'
		]
	],
	denormalizationContext: [['groups'=>'write:User']],
	normalizationContext: [['groups'=>'read:User']]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
	#[Groups(['read:User'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
	#[Groups(['read:User', 'write:User', 'put:itemUser'])]
	#[Assert\NotNull(
		message: 'Champ email  vide'
	)]
	#[Assert\Email(
		message: 'Email invalide'
	)]
    private ?string $email = null;

	#[Groups(['read:User'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
	#[Groups(['write:User', 'put:password'])]
	#[Assert\NotBlank(
		message: 'Champ password  vide'
	)]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
	#[Groups(['read:User', 'write:User', 'put:itemUser'])]
	#[Assert\NotBlank(
		message: 'Champ prenom vide'
	)]
    private ?string $prenom = null;

    #[ORM\Column(length: 50)]
	#[Groups(['read:User', 'write:User', 'put:itemUser'])]
	#[Assert\NotBlank(
		message: 'Champ nom vide'
	)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
	#[Groups(['read:User', 'write:User', 'put:itemUser'])]
	#[Assert\NotBlank(
		message: 'Champ pseudo vide'
	)]
    private ?string $pseudo = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
	#[Groups(['write:User', 'put:avatar'])]
    private $avatar = null;

    #[ORM\Column(length: 100, nullable: true)]
	#[Groups(['read:User', 'write:User', 'put:itemUser'])]
    private ?string $adresse = null;

    #[ORM\Column(length: 100, nullable: true)]
	#[Groups(['read:User', 'write:User', 'put:itemUser'])]
    private ?string $telephone = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
	#[Groups(['write:User'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column]
	#[Groups(['read:User'])]
    private ?bool $isDeleted = null;

    #[ORM\ManyToOne( inversedBy: 'users')]
	#[Groups(['read:User','write:User' ])]
    private ?Profile $profile = null;

	public function __construct()
	{
		$this->createdAt = new \DateTime();
		$this->isDeleted = false;
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
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        //$roles[] = 'ROLE_USER';
		$roles[] = 'ROLE_'.$this->profile->getLibelle();

        return array_unique($roles);
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
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }
}
