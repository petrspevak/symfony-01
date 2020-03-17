<?php

namespace App\Tests\Mailer;

use App\Entity\User;
use App\Mailer\Mailer;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class MailerTest extends TestCase
{
    public function testSendConfirmationEmail()
    {
        $user = new User();
        $user->setEmail('user@test.com');

        $swiftMailerMock = $this->getMockBuilder(\Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $twigMock = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $twigMock->expects($this->once())
            ->method('render')
            ->with(
                'email/registration.html.twig',
                ['user' => $user]
            )
            ->willReturn('');
        ; // toto je assertion

        $swiftMailerMock->expects($this->once())
            ->method('send')
            ->with($this->callback(static function ($subject) use ($user) {
                $messageStr = (string)$subject;

                return (bool)mb_strpos($messageStr, 'Subject: Welcome to the micro-post app!')
                    && (bool)mb_strpos($messageStr, 'From: test@test.com')
                    && (bool)mb_strpos($messageStr, 'To: ' . $user->getEmail())
                ;
            }))
        ; // toto je dalsi asssertion

        $mailer = new Mailer($swiftMailerMock, $twigMock, 'test@test.com');
        $mailer->sendConfirmationEmail($user);
    }
}
