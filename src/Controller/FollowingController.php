<?php /** @noinspection TypeUnsafeComparisonInspection */

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("has_role('ROLE_USER')")
 */
class FollowingController extends AbstractController
{
    /**
     * @Route("/follow/{id}", name="following_follow")
     * @param User $user
     * @return RedirectResponse
     */
    public function follow(User $user): RedirectResponse
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();


        if ($currentUser != $user) {
            /** @noinspection NullPointerExceptionInspection */
            $currentUser->getFollowing()->add($user);
        }

        return $this->flushAndRedirect($user);
    }

    /**
     * @Route("/unfollow/{id}", name="following_unfollow")
     * @param User $user
     * @return RedirectResponse
     */
    public function unfollow(User $user): RedirectResponse
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        /** @noinspection NullPointerExceptionInspection */
        $currentUser->getFollowing()->removeElement($user);

        return $this->flushAndRedirect($user);
    }

    private function flushAndRedirect(User $user): RedirectResponse
    {
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('micro_post_user', ['username' => $user->getUsername()]);
    }
}
