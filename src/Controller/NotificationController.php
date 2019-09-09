<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\User;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_USER')")
 * @Route("/notification")
 */
class NotificationController extends AbstractController
{
    /**
     * @var NotificationRepository
     */
    private $notificationRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(NotificationRepository $notificationRepository, EntityManagerInterface $entityManager)
    {
        $this->notificationRepository = $notificationRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/unread-count", name="notification_unread_count")
     * @throws NonUniqueResultException
     */
    public function unreadCount(): JsonResponse
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        return new JsonResponse([
            'count' => $this->notificationRepository->findUnseenByUserCount($currentUser)
        ]);
    }

    /**
     * @Route("/all", name="notification_all")
     */
    public function notifications(): Response
    {
        return $this->render('notification/notifications.html.twig', [
            'notifications' => $this->notificationRepository->findBy([
                'seen' => false,
                'user' => $this->getUser()
            ])
        ]);
    }

    /**
     * @Route("/acknowledge/{id}", name="notification_acknowledge")
     */
    public function acknowledge(Notification $notification): RedirectResponse
    {
        $notification->setSeen(true);
        $this->entityManager->flush();

        return $this->redirectToRoute('notification_all');
    }

    /**
     * @Route("/acknowledge-all", name="notification_acknowledge_all")
     */
    public function acknowledgeAll()
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $this->notificationRepository->markAllAsReadByUser($currentUser);
        $this->entityManager->flush();

        return $this->redirectToRoute('notification_all');
    }
}