<?php
namespace Zgh\FEBundle\Service;

use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Zgh\FEBundle\Entity\User;
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

    /**
     * @var \Symfony\Bridge\Twig\TwigEngine
     */
    protected $templating;

    public function __construct(\Swift_Mailer $mailer, RouterInterface $routerInterface, TwigEngine $engine)
    {
        $this->mailer = $mailer;
        $this->router = $routerInterface;
        $this->templating = $engine;
    }

    public function sendNotification($event)
    {
        try {
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
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    protected function sendCommentNotification(NotifyCommentEvent $event)
    {
        $user = $event->getUserToNotify();
        $notification = $event->getNotification();
        $url = $this->router->generate("zgh_fe.post.display", ["id" => $user->getId(), "post_id" => $notification->getContent()["obj_id"]], true);
        $body = "{$notification->getContent()["user"]} has commented on your post - {$url}";
        $this->send("Someone commented on your post", $user->getEmail(), $body);
    }

    protected function sendLikeNotification(NotifyLikeEvent $event)
    {
        $user = $event->getUserToNotify();
        $notification = $event->getNotification();
        $url = $this->router->generate("zgh_fe.post.display", ["id" => $user->getId(), "post_id" => $notification->getContent()["obj_id"]], true);
        $body = "{$notification->getContent()["user"]} has liked your post - {$url}";
        $this->send("Someone liked your post", $user->getEmail(), $body);
    }

    protected function sendFollowNotification(NotifyFollowEvent $event)
    {
        $user = $event->getUserToNotify();
        $notification = $event->getNotification();
        $url = $this->router->generate("zgh_fe.user_profile.index", ["id" => $notification->getContent()["follower_id"]], true);
        $title = "{$notification->getContent()["user"]} has followed you.";
        $body = sprintf("<a href='%s'>{$notification->getContent()["user"]}</a> has followed you.", $url);
        $this->send($title, $user->getEmail(), $body);
    }

    protected function sendFollowRequestNotification(NotifyFollowRequestEvent $event)
    {
        $user = $event->getUserToNotify();
        $notification = $event->getNotification();
        $url = $this->router->generate("zgh_fe.user_profile.index", ["id" => $notification->getContent()["follower_id"]], true);
        $title = "{$notification->getContent()["user"]} wants to follow you.";
        $body = sprintf("<a href='%s'>{$notification->getContent()["user"]}</a> wants to follow you.", $url);
        $this->send($title, $user->getEmail(), $body);
    }

    protected function sendRelationshipRequest(NotifyRelationshipRequestEvent $event)
    {
        $user = $event->getUserToNotify();
        $notification = $event->getNotification();
        $url = $this->router->generate("zgh_fe.user_profile.index", ["id" => $notification->getContent()["requester_id"]], true);
        $hisOrHer = $notification->getContent()["requester_gender"] == 0 ? "his" : "her";
        $body = "{$notification->getContent()["user"]} wants to add you in {$hisOrHer} relationship information.";
        $this->send("Someone wants to add you in {$hisOrHer} relationship information", $user->getEmail(), $body);
    }

    protected function send($subject, $email, $notification_body)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom("notifications@zaghruta.com")
            ->setTo($email)
            ->setBody(
                $this->templating->render("@ZghFE/Default/notification_email.txt.twig", [
                    "notification_type" => $subject,
                    "notification_body" => $notification_body
                ]),
                'text/html'
            );

        try {
            $this->mailer->send($message);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public  function sendEmailChangeConfirmation(User $user, $conf_email)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject("Email change confirmation")
            ->setFrom("notifications@zaghruta.com")
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render("@ZghFE/Default/email_change_confirmation.txt.twig", [
                    "email_body" => sprintf("Please click on the link below to confirm that %s is your desired email.", $user->getNewEmail()),
                    "conf_email" => $conf_email
                ]),
                'text/html'
            );

        try {
            $this->mailer->send($message);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}