<?php
namespace Zgh\FEBundle\Service;

use Symfony\Component\Routing\RouterInterface;
use Zgh\FEBundle\Model\Event\NotifyCommentEvent;
use Zgh\FEBundle\Model\Event\NotifyFollowEvent;
use Zgh\FEBundle\Model\Event\NotifyFollowRequestEvent;
use Zgh\FEBundle\Model\Event\NotifyLikeEvent;
use Zgh\FEBundle\Model\Event\NotifyRelationshipRequestEvent;

class EmailNotifier
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    protected $router;

    public function __construct(\Swift_Mailer $mailer, RouterInterface $routerInterface)
    {
        $this->mailer = $mailer;
        $this->router = $routerInterface;
    }

    public function sendNotification($event)
    {
        if ($event instanceof NotifyCommentEvent) {
            $this->sendCommentNotification($event);
        } else if ($event instanceof NotifyFollowEvent) {
            $this->sendFollowNotification($event);
        } else if ($event instanceof NotifyLikeEvent) {
            $this->sendLikeNotification($event);
        } else if ($event instanceof NotifyFollowRequestEvent) {
            $this->sendFollowRequestNotification($event);
        } else if ($event instanceof NotifyRelationshipRequestEvent) {
            $this->sendRelationshipRequest($event);
        }
    }

    protected function sendCommentNotification(NotifyCommentEvent $event)
    {
        $user = $event->getUserToNotify();
        $notification = $event->getNotification();
        $url = $this->router->generate("zgh_fe.post.display", ["id" => $user->getId(), "post_id" => $notification->getContent()["post_id"]], true);
        $message = \Swift_Message::newInstance()
            ->setSubject("Someone liked your post")
            ->setFrom("notifications@zaghruta.com")
            ->setTo($user->getEmail())
            ->setBody("{$notification->getContent()["user"]} has commented on your post - {$url}")
        ;
        $this->mailer->send($message);
    }
}