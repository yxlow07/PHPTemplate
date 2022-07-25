<?php

namespace app\mail;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer as SymfonyMailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class Mailer
{
    public function __construct(protected string $mailerDsn)
    {
    }

    #[ArrayShape(["status" => "bool", "msg" => "string"])]
    public function sendMail($from, $to, $subject, $text = "", $html = ""): array
    {
        $email = new Email();
        $transport = Transport::fromDsn($this->mailerDsn);
        $mailer = new SymfonyMailer($transport);

        $email
            ->from($from)
            ->to(new Address($to))
            ->subject($subject)
            ->text($text)
            ->html($html);

        try {
            $mailer->send($email);
            return ["status" => true, "msg" => "Mail sent successfully"];
        } catch (TransportExceptionInterface $e) {
            return ["status" => false, "msg" => $e->getMessage()];
        }
    }
}