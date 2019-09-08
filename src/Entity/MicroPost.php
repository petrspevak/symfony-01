<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MicroPostRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class MicroPost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=280)
     * @Assert\NotBlank()
     * @Assert\Length(min=10)
     */
    private $text;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $time;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="microPostsLiked")
     * @ORM\JoinTable(name="post_likes",
     *     joinColumns={@ORM\JoinColumn(name="micro_post_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")})
     */
    private $likedBy;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="microPosts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


    public function __construct()
    {
        $this->likedBy = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): MicroPost
    {
        $this->text = $text;
        return $this;
    }

    public function getTime(): ?DateTime
    {
        return $this->time;
    }

    public function setTime(DateTime $time): MicroPost
    {
        $this->time = $time;
        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setTimeOnPersist(): void
    {
        $this->time = new DateTime();
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): MicroPost
    {
        $this->user = $user;
        return $this;
    }

    public function getLikedBy(): Collection
    {
        return $this->likedBy;
    }

    public function like(User $user): self
    {
        if(!$this->likedBy->contains($user)) {
            $this->likedBy->add($user);
        }
        return $this;
    }
}
