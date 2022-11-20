<?php

namespace App\Controller;

use App\Entity\Demandes;
use App\Service\AddDemandes;
use App\Service\SmsSendNotification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UpdateDemandeController
{
	public function __construct()
	{
	}

	public function __invoke(
		Demandes $data,
		Request $request,
		AddDemandes $addDemandes,
		ObjectNormalizer $normalizer,
		EntityManagerInterface $manager
	)
	{
		$demandeTab = json_decode($request->getContent(), true);
		return $data;
	}
}