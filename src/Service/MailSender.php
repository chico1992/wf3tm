<?php

namespace App\Service;

use App\Entity\User;


class MailSender
{
    private $twig;
    private $mailer;

    public function __construct(
        \Twig_Environment $twig,
        \Swift_Mailer $mailer
    )
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    public function send(User $user)
    {
        $message = (new \Swift_Message('Registration Mail'))
            ->setFrom('julien@julien.fr')
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render(
                    // templates/emails/registration.html.twig
                    'emails/registration.html.twig',
                    array('user' => $user)
                ),
                'text/html'
            )
            ->addPart(
                $this->twig->render(
                    'emails/registration.txt.twig',
                    array('user' => $user)
                ),
                'text/plain'
            )
        ;

        $this->mailer->send($message);
    }
}