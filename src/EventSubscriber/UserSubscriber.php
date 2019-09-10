<?php

namespace App\EventSubscriber;

use App\Event\UserRegisterEvent;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;

class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserRegisterEvent::NAME => 'onUserRegister'
        ];
    }

    public function onUserRegister(UserRegisterEvent $event)
    {
        $registeredUser = $event->getRegisteredUser();

        $body = $this->twig->render('email/registration.html.twig', ['user' => $registeredUser]);

        $message = (new Swift_Message())
            ->setSubject('Welcome to the micro-post app!')
            ->setTo($registeredUser->getEmail())
            ->setBody($body, 'text/html');

        $this->mailer->send($message);
    }
}
