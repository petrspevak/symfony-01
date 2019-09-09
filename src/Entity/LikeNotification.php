<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LikeNotificationRepository")
 */
class LikeNotification extends Notification
{
    /**
     * @var MicroPost|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\MicroPost")
     */
    private $microPost;

    /**
     * @var User|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $likedBy;

    /**
     * @return MicroPost|null
     */
    public function getMicroPost(): ?MicroPost
    {
        return $this->microPost;
    }

    /**
     * @param MicroPost|null $microPost
     * @return LikeNotification
     */
    public function setMicroPost(?MicroPost $microPost): LikeNotification
    {
        $this->microPost = $microPost;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getLikedBy(): ?User
    {
        return $this->likedBy;
    }

    /**
     * @param User|null $likedBy
     * @return LikeNotification
     */
    public function setLikedBy(?User $likedBy): LikeNotification
    {
        $this->likedBy = $likedBy;
        return $this;
    }
}
