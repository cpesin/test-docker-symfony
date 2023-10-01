<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Contact;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * [Description Mailer].
 */
class Mailer
{
    /**
     * [Description for $mailer].
     */
    private MailerInterface $mailer;

    /**
     * [Description for __construct].
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * [Description for sendEmail].
     */
    public function sendContactEmail(Contact $data): int
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('email-de-test@test.com')
            ->subject('Contact : '.$data->getFirstname().' '.$data->getLastname())
            ->text('name: '.$data->getFirstname().' '.$data->getLastname()."\nemail: ".$data->getEmail()."\nmessage: \n".$data->getMessage())
            ->html('<p>name: '.$data->getFirstname().' '.$data->getLastname().'</p><p>email: '.$data->getEmail().'</p><p>message: '.$data->getMessage().'</p>');

        try {
            $this->mailer->send($email);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return 0;
    }
}
