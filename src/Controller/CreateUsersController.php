<?php

namespace App\Controller;

use App\Service\AddUsers;
use Symfony\Component\HttpFoundation\Request;


class CreateUsersController
{

	public function __construct()
	{

	}

	public function __invoke(
		Request $request,
		AddUsers $addUsers
	)
	{
		$usersTab = $request->request->all();
		$avatar = $request->files->get("avatar");
		if($avatar !== null){
			$usersTab['avatar'] = fopen($avatar->getRealPath(),"rb");
		}
		return $addUsers->serviceAddUser($usersTab);

	}

}