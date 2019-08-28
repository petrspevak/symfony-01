<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MicroPostRepository")
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
     */
    private $text;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $time;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return MicroPost
     */
    public function setText(string $text): MicroPost
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getTime(): ?DateTime
    {
        return $this->time;
    }

    /**
     * @param DateTime $time
     * @return MicroPost
     */
    public function setTime(DateTime $time): MicroPost
    {
        $this->time = $time;
        return $this;
    }
}
