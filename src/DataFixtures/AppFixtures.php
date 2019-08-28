<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;

class AppFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function load(ObjectManager $manager) : void
    {
        for ($i=1; $i < 10; $i++) {
            $microPost = new MicroPost();
            $microPost
                ->setText('Nahodny text' . random_int(1, 10000))
                ->setTime(new DateTime());
            $manager->persist($microPost);
        }

        $manager->flush();
    }
}
