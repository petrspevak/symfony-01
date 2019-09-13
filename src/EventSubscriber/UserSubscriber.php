<?php

namespace App\EventSubscriber;

use App\Entity\UserPreferences;
use App\Event\UserRegisterEvent;
use App\Mailer\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var Mailer
     */
    private $mailer;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var string
     */
    private $defaultLocale;

    public function __construct(Mailer $mailer, EntityManagerInterface $entityManager, string $defaultLocale)
    {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->defaultLocale = $defaultLocale;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserRegisterEvent::NAME => 'onUserRegister'
        ];
    }

    /**
     * @param UserRegisterEvent $event
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function onUserRegister(UserRegisterEvent $event): void
    {
        $preferences = new UserPreferences();
        $preferences->setLocale($this->defaultLocale);

        $registeredUser = $event->getRegisteredUser();
        $registeredUser->setPreferences($preferences);
        
        $this->entityManager->flush();

        $this->mailer->sendConfirmationEmail($registeredUser);
    }
}
