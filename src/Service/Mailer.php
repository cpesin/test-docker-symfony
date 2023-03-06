<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * [Description Mailer].
 */
class Mailer
{
    /**
     * [Description for $mailer].
     *
     * @var MailerInterface
     */
    private $mailer;

    /**
     * [Description for __construct].
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * [Description for sendEmail].
     *
     * @param mixed $data
     */
    public function sendEmail($data): int
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('email-de-test@test.com')
            ->subject('[test] Time for Symfony Mailer!')
            ->text('name: '.$data['name']."\nemail: ".$data['email']."\nmessage: \n".$data['message'])
            ->html('<p>name: '.$data['name'].'</p><p>email: '.$data['email'].'</p><p>message: '.$data['message'].'</p>');

        try {
            $this->mailer->send($email);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return 0;
    }
}
