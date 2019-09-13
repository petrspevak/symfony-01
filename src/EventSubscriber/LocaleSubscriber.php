<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private $defaultLocale;

    public function __construct($defaultLocale = 'en')
    {
        $this->defaultLocale = $defaultLocale;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                'onKernelRequest',
                20
            ]
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$request->hasPreviousSession()) {
            return;
        }

        if ($locale = $request->attributes->get('_locale')) {
            /** @noinspection NullPointerExceptionInspection */
            $request->getSession()->set('_locale', $locale);
        } else {

            /** @noinspection NullPointerExceptionInspection */
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }
    }
}
