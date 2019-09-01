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
    private const USERS = [
        [
            'username' => 'john_doe',
            'email' => 'john_doe@doe.com',
            'password' => 'john123',
            'fullName' => 'John Doe',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'rob_smith',
            'email' => 'rob_smith@smith.com',
            'password' => 'rob12345',
            'fullName' => 'Rob Smith',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'marry_gold',
            'email' => 'marry_gold@gold.com',
            'password' => 'marry12345',
            'fullName' => 'Marry Gold',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'password' => 'admin123',
            'fullName' => 'Micro Admin',
            'roles' => [User::ROLE_ADMIN]
        ],
    ];

    private const POST_TEXT = [
        'Hello, how are you?',
        'It\'s nice sunny weather today',
        'I need to buy some ice cream!',
        'I wanna buy a new car',
        'There\'s a problem with my phone',
        'I need to go to the doctor',
        'What are you up to today?',
        'Did you watch the game yesterday?',
        'How was your day?'
    ];

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
        foreach (self::USERS as $userData) {
            $user = new User();
            $user
                ->setUsername($userData['username'])
                ->setFullName($userData['fullName'])
                ->setEmail($userData['email'])
                ->setPassword($this->passwordEncoder->encodePassword($user, $userData['password']))
                ->setRoles($userData['roles'])
            ;

            $this->addReference($userData['username'], $user);

            $manager->persist($user);
        }
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    private function loadMicroPosts(ObjectManager $manager): void
    {
        for ($i = 1; $i < 30; $i++) {
            $dateTime = new DateTime();
            $dateTime->modify('- ' . random_int(0, 50) . ' day');

            $microPost = new MicroPost();
            $microPost
                ->setText(self::POST_TEXT[random_int(0, count(self::POST_TEXT) - 1)])
                ->setTime($dateTime)
                ->setUser($this->getReference(self::USERS[random_int(0, count(self::USERS) - 1)]['username']));

            $manager->persist($microPost);
        }

        $manager->flush();
    }
}
