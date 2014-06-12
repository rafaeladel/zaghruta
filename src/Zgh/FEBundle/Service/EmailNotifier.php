<?php
namespace Zgh\FEBundle\Service;

class EmailNotifier
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendNotification($event)
    {
        $user = $event->getUserToNotify();

        $message = \Swift_Message::newInstance()
                        ->setSubject("Notification from zagh")
                        ->setFrom("notification@zaghruta.com")
                        ->setTo($user->getEmail())
                        ->setBody("tesswt")
            ;
            $this->mailer->send($message);
    }
}