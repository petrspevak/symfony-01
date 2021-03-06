<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/micro-post")
 */
class MicroPostController extends AbstractController
{
    /**
     * @var MicroPostRepository
     */
    private $microPostRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * MicroPostController constructor.
     * @param MicroPostRepository $microPostRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(MicroPostRepository $microPostRepository, EntityManagerInterface $entityManager)
    {
        $this->microPostRepository = $microPostRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="micro_post_index")
     */
    public function index(UserRepository $userRepository): Response
    {
        $currentUser = $this->getUser();
        $usersToFollow = [];

        if ($currentUser instanceof User) {
            $posts = $this->microPostRepository->findAllByUsers($currentUser->getFollowing());

            $usersToFollow = count($posts) === 0 ? $userRepository->findAllWithMoreThan5PostsExceptUser($currentUser) : [];
        }
        else {
            $posts = $this->microPostRepository->findBy([], ['time' => 'DESC']);
        }

        return $this->render('micro-post/index.html.twig', [
            'posts' => $posts,
            'usersToFollow' => $usersToFollow
        ]);
    }

    /**
     * @Route("/delete/{id}", name="micro_post_delete")
     * @param MicroPost $microPost
     * @return RedirectResponse
     */
    public function delete(MicroPost $microPost): RedirectResponse
    {
        if (!$this->isGranted('delete', $microPost)) {
            throw new AccessDeniedException();
        }
        $this->entityManager->remove($microPost);
        $this->entityManager->flush();

        $this->addFlash('notice', 'Micro-post was deleted.');

        return $this->redirectToRoute('micro_post_index');
    }

    /**
     * @Route("/edit/{id}", name="micro_post_edit")
     * @param MicroPost $microPost
     * @param Request $request
     * @return Response
     *
     * @Security("is_granted('edit', microPost)")
     */
    public function edit(MicroPost $microPost, Request $request): Response
    {
//        $this->denyAccessUnlessGranted('edit', $microPost); // dalsi moznost kontroly
        return $this->form($microPost, $request);
    }

    /**
     * @Route("/add", name="micro_post_add")
     * @param Request $request
     * @return Response
     * @throws Exception
     *
     * @Security("has_role('ROLE_USER')")
     */
    public function add(Request $request): Response
    {
        $microPost = new MicroPost();
        $microPost->setUser($this->getUser());

        return $this->form($microPost, $request);
    }

    private function form(MicroPost $microPost, Request $request)
    {
        $form = $this->createForm(MicroPostType::class, $microPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($microPost);
            $this->entityManager->flush();

            return $this->redirectToRoute('micro_post_index');
        }

        return $this->render(
            'micro-post/add.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/user/{username}", name="micro_post_user")
     */
    public function userPosts(User $userWithPosts)
    {
        return $this->render('micro-post/user-posts.html.twig', [
            'posts' => $this->microPostRepository->findBy(['user' => $userWithPosts], ['time' => 'DESC']),
//            'posts' => $userWithPosts->getMicroPosts(),
            'user' => $userWithPosts
        ]);
    }

    /**
     * Tato metoda musi byt na konci, jinak tato route spolkne i odkazy na jine.
     *
     * @param MicroPost $microPost
     * @return Response
     * @Route("/{id}", name="micro_post_show")
     */
    public function show(MicroPost $microPost): Response
    {
        return $this->render('micro-post/show.html.twig', ['post' => $microPost]);
    }
}