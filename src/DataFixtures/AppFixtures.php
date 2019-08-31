<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadMicroPosts($manager);
    }

    private function loadUsers(ObjectManager $manager): void
    {
        $user = new User();
        $user
            ->setUsername('john')
            ->setFullName('John Doe')
            ->setEmail('john@doe.com')
            ->setPassword($this->passwordEncoder->encodePassword($user, 'john123'));
        $this->addReference('john', $user);

        $manager->persist($user);
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    private function loadMicroPosts(ObjectManager $manager): void
    {
        for ($i = 1; $i < 10; $i++) {
            $microPost = new MicroPost();
            $microPost
                ->setText('Nahodny text ' . random_int(1, 10000))
                ->setTime(new DateTime())
                ->setUser($this->getReference('john'));
            $manager->persist($microPost);
        }

        $manager->flush();
    }
}
