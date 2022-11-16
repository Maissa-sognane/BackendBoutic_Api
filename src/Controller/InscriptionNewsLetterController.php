<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\NewsLetter;
use App\Repository\UserRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class InscriptionNewsLetterController
{
	public function __construct(){}

	public function __invoke(
		Request $request,
		EntityManagerInterface $manager,
		MailerService $mailerService,
		UserRepository $userRepository,
		ValidatorInterface $validator
	)
	{
		$dataUser = json_decode($request->getContent(), true);
		$email = $dataUser['email'];
		$content = '
				<h2>Bonjour, </h2>
				<p style="font-size: 20px">
				Merci d’avoir rejoint le NewsLetter de <strong>BOUTIC</strong>.<br>
				Nous aimerions vous confirmer que votre inscription est terminé avec succès.<br>
				Si vous rencontrez des difficultés pour vous connecter à votre compte, contactez-nous à noreplyboutic@gmail.com.<br>
				Cordialement,<br>
				L’équipe du BOUTIC
				</p>';
		$subjet = 'Votre inscription au newsletter Burotic est terminée';
		$newsLetter = new NewsLetter();
		$newsLetter->setEmail($email);
		$errorsCount = $validator->validate($newsLetter)->count();
		$validatorErrorTab = $validator->validate($newsLetter) ;

		if($errorsCount !== 0){
			return new JsonResponse(
				$validatorErrorTab[0]->getConstraint()->message,
				Response::HTTP_INTERNAL_SERVER_ERROR,
				headers: ['Content-Type', 'application/text']
			);
		}else{
			$manager->persist($newsLetter);
			$manager->flush();
			$mailerService->sendEmail(
				$email,
				'maissasognane@gmail.com',
				$content,
				$subjet
			);
			return new JsonResponse(
				'Inscription successful',
				Response::HTTP_OK,
				headers: ['Content-Type', 'application/json']
			);

		}
	}
}