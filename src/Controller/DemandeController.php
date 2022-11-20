<?php

namespace App\Controller;

use App\Entity\Demandes;
use App\Repository\StatutRepository;
use App\Service\AddDemandes;
use App\Service\MailerService;
use App\Service\SmsSendNotification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class DemandeController
{
	public function __construct()
	{
	}

	public function __invoke(
		Request $request,
		Demandes $demandes,
		MailerService $mailerService,
		EntityManagerInterface $manager,
		ValidatorInterface $validator,
		StatutRepository $statutRepository,
		Normalizer\ObjectNormalizer $normalizer,
		SmsSendNotification $smsSendNotification,
		AddDemandes $addDemandes

	): JsonResponse
	{
		$dataDemande = $request->getContent();
		$dataDemandeTab = json_decode($dataDemande, true);
		$email = $dataDemandeTab['emailRepresentant'];
		$subjet = 'Demande de devis';
		$content = '
				<h2>Bonjour,'.$dataDemandeTab['representant'].'</h2>
				<p style="font-size: 20px">
				Merci pour votre demande de devis chez <strong>BOUTIC</strong>.<br>
				Nous aimerions vous confirmer que votre demande est enrégistrée avec succès.<br>
				Si vous avez des questions, contactez-nous à noreplyboutic@gmail.com.<br>
				Cordialement,<br>
				L’équipe du BOUTIC
				</p>';



		$machinesTab = $dataDemandeTab['machines'];
		$dataDemandeTab['statut'] = $addDemandes->addStatut($dataDemandeTab['statut']);

		$demandeJson = new Demandes();
		$addDemandes->addTypeMachines($machinesTab, $demandeJson);

		$demandeJson->setPrenom($dataDemandeTab['prenom']);
		$demandeJson->setNom($dataDemandeTab['nom']);
		$demandeJson->setAdresseEntreprise($dataDemandeTab['adresseEntreprise']);
		$demandeJson->setEmailRepresentant($dataDemandeTab['emailRepresentant']);
		$demandeJson->setRepresentant($dataDemandeTab['representant']);
		$demandeJson->setNombreEmployee($dataDemandeTab['nombreEmployee']);
		$demandeJson->setStatut($dataDemandeTab['statut']);
		$demandeJson->setNumeroTelephoneEntreprise($dataDemandeTab['numeroTelephoneEntreprise']);
		$demandeJson->setNumeroTelephoneRepresentant($dataDemandeTab['numeroTelephoneRepresentant']);
		$errorsCount = $validator->validate($demandeJson)->count();

		if($errorsCount !== 0){
			return new JsonResponse(
				$validator->validate($demandeJson)[0]->getMessage(),
				Response::HTTP_INTERNAL_SERVER_ERROR,
				headers: ['Content-Type', 'application/json']
			);
		}else{


				$manager->persist($demandeJson);
				$manager->flush();
				$mailerService->sendEmail(
					$email,
					'maissasognane@gmail.com',
					$content,
					$subjet
				);
			return new JsonResponse(
				'Demande Envoye avec success',
				Response::HTTP_CREATED,
				headers: ['Content-Type', 'application/json']
			);
		}

	}
}
