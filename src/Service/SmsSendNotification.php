<?php

namespace App\Service;

use Infobip\Configuration;
use Infobip\Api\SendSmsApi;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class SmsSendNotification extends AbstractController
{
	public function __construct()
	{
	}

	public function sendSms($numbTelephone, $content)
	{
		// 1. Create configuration object and client
		$baseurl = $this->getParameter("sms_gateway.baseurl");
		$apikey = $this->getParameter("sms_gateway.apikey");
		$apikeyPrefix = $this->getParameter("sms_gateway.apikeyprefix");

		$configuration = (new Configuration())
			->setHost($baseurl)
			->setApiKeyPrefix('Authorization', $apikeyPrefix)
			->setApiKey('Authorization', $apikey);

		$client = new \GuzzleHttp\Client();
		$sendSmsApi = new SendSMSApi($client, $configuration);

		// 2. Create message object with destination
		$destination = (new SmsDestination())->setTo($numbTelephone);
		$message = (new SmsTextualMessage())
			// Alphanumeric sender ID length should be between 3 and 11 characters (Example: `CompanyName`).
			// Numeric sender ID length should be between 3 and 14 characters.
			->setFrom('BOUTIC')
			->setText($content)
			->setDestinations([$destination]);

		// 3. Create message request with all the messages that you want to send
		$request = (new SmsAdvancedTextualRequest())->setMessages([$message]);

		// 4. Send !
		try {
			$smsResponse = $sendSmsApi->sendSmsMessage($request);

			//dump($smsResponse);
		}catch (\Throwable $apiException) {
			// HANDLE THE EXCEPTION
			dump($apiException);
		}

	}

}