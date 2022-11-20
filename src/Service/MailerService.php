<?php

namespace App\Service;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class MailerService
{
	public MailerInterface $mailer;

	public function __construct( MailerInterface $mailer)
	{
		$this->mailer = $mailer;
	}

	/**
	 * @throws TransportExceptionInterface
	 */
	public function sendEmail(
		$to,
		$cc,
		$content,
		$subject

	)
	{
		$email = (new Email())
			->from('noreplyboutic@gmail.com')
			->to($to)
			->cc($cc)
			//->bcc('bcc@example.com')
			//->replyTo('fabien@example.com')
			//->priority(Email::PRIORITY_HIGH)
			->subject($subject)
			->text('Sending emails is fun again!')
			->html($content);

		$this->mailer->send($email);
	}
}