<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(/likes)
 */
class LikeController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/like/{id}", name="likes_like")
     */
    public function like(MicroPost $microPost): JsonResponse
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if (!$currentUser instanceof User) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }

        $microPost->like($currentUser);
        $this->entityManager->flush();

        return new JsonResponse([
           'count' => $microPost->getLikedBy()->count()
        ]);
    }

    /**
     * @Route("/unlike/{id}", name="likes_unlike")
     */
    public function unlike(MicroPost $microPost): JsonResponse
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if (!$currentUser instanceof User) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }

        $microPost->getLikedBy()->removeElement($currentUser);
        $this->entityManager->flush();

        return new JsonResponse([
            'count' => $microPost->getLikedBy()->count()
        ]);
    }
}