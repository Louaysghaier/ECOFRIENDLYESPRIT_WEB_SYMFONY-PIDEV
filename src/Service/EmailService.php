<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendSimpleEmail(string $recipient, string $subject, string $body): void
    {
        $email = (new Email())
            ->from('louaysghaier01@gmail.com')
            ->to($recipient)
            ->subject($subject)
            ->text($body);

        $this->mailer->send($email);
    }

    public function sendTemplatedEmail(string $recipient, string $subject, array $context): void
    {
        // Similar to the example in previous responses
        // ...
    }
}