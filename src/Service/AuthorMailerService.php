<?php

namespace App\Service;

use App\Entity\Author;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class AuthorMailerService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function notifyAuthor(Author $author): void
    {
        $email = (new Email())
            ->from('admin@yourapp.com')
            ->to($author->getEmail())
            ->subject('New Book Added!')
            ->text("Dear {$author->getUsername()},\n\nA new book has been added to your collection!");

        $this->mailer->send($email);
    }
}
