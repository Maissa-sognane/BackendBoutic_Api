<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\AddUsers;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdatePasswordUserController
{
	public function __construct()
	{

	}

	public function __invoke(
		Request $request,
		User $data,
		EntityManagerInterface $manager,
		AddUsers $addUsers,
	)
	{
		$passwordTab = json_decode($request->getContent(), true);
		$passwordUser = $passwordTab['password'];
	  	$passwordSet = $addUsers->hasherPasswordUser($passwordUser, $data);

		$manager->persist($data);
		$manager->flush();

		return new JsonResponse(
			'Password Updated',
			Response::HTTP_OK,
			headers: ['Content-Type', 'application/json']
		);
	}
}