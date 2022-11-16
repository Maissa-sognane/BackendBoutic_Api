<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddUsers
{
	private UserPasswordHasherInterface $passwordHasher;
	private EntityManagerInterface $manager;
	private Normalizer\ObjectNormalizer $normalizer;
	private ValidatorInterface $validator;

	public function __construct(
		UserPasswordHasherInterface $passwordHasher,
		EntityManagerInterface $manager,
		Normalizer\ObjectNormalizer $normalizer,
		ValidatorInterface $validator
	)
	{
		$this->normalizer = $normalizer;
		$this->manager = $manager;
		$this->passwordHasher = $passwordHasher;
		$this->validator = $validator;

	}
	public function serviceAddUser( $usersTab)
	{
		$usersJson = $this->normalizer->denormalize($usersTab, User::class, 'json');
		if($usersTab['password']){
			$password = $usersJson->getPassword();
			$usersJson->setPassword($this->passwordHasher->hashPassword($usersJson, $password));
		}
		$usersJson->setIsDeleted(false);
		$errorsCount = $this->validator->validate($usersJson)->count();
		$validatorErrorTab = $this->validator->validate($usersJson);
		if($errorsCount !== 0){
			return new Response(
				$validatorErrorTab[0]->getMessage(),
				Response::HTTP_INTERNAL_SERVER_ERROR,
				headers: ['Content-Type', 'application/text']
			);
		}else{
			$this->manager->persist($usersJson);
			$this->manager->flush();
			return new JsonResponse(
				'Creation Successful',
				Response::HTTP_CREATED,
				headers: ['Content-Type', 'application/json']
			);
		}
	}

	public function hasherPasswordUser($passwordUser, $dataUser)
	{
			if($passwordUser)
			{
				$password = $dataUser->getPassword();
				return 	$dataUser->setPassword(
					$this->passwordHasher->hashPassword($dataUser, $password));
			}
	}
}