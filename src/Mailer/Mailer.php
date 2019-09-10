<?php

namespace App\Mailer;

use App\Entity\User;
use Swift_Mailer;
use Swift_Message;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Mailer
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var string
     */
    private $mailFrom;

    public function __construct(Swift_Mailer $mailer, Environment $twig, string $mailFrom)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->mailFrom = $mailFrom;
    }

    /**
     * @param User $registeredUser
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function sendConfirmationEmail(User $registeredUser): void
    {

        $body = $this->twig->render('email/registration.html.twig', ['user' => $registeredUser]);

        $message = (new Swift_Message())
            ->setSubject('Welcome to the micro-post app!')
            ->setFrom($this->mailFrom)
            ->setTo($registeredUser->getEmail())
            ->setBody($body, 'text/html');

        $this->mailer->send($message);
    }
}
