<?php

namespace App\EventListener;

use App\Event\MailEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EventToMailConnection implements EventSubscriberInterface
{
	static function getSubscribedEvents(){
		return [
			MailEvent::SEND_MAIL => 'envoiDuMail'
		];
	}

	public function envoiDuMail() {
		$transport = new \Swift_SmtpTransport();

		$message = (new \Swift_Message())
			->setSubject('sujet')
			->setFrom('clessili@gmail.com')
			->setTo('clessili@gmail.com')
			->setBody('hello')
		;

		$mailer = new \Swift_Mailer($transport);
		$mailer->send($message);
	}
}