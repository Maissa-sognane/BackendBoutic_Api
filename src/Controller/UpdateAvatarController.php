<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;



class UpdateAvatarController
{
	public function __construct()
	{

	}
	public function __invoke(
		Request $request,
		User $data,
		EntityManagerInterface $manager
	)
	{
		$usersTab = $request->request->all();
		$avatar = $request->files->get("avatar");
		if($avatar !== null){
			$usersTab['avatar'] = fopen($avatar->getRealPath(),"rb");
			$data->setAvatar($usersTab['avatar']);
			$manager->persist($data);
			$manager->flush();

		}
		 return new JsonResponse(
			 'Update Successful',
			 Response::HTTP_OK,
			 headers: ['Content-Type', 'application/json']
		 );
	}

}