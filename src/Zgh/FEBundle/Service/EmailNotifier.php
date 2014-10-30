<?php
namespace Zgh\FEBundle\Service;

use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Zgh\FEBundle\Entity\Notification;
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

    /**
     * @var Notification
     */
    protected $notification;

    public function __construct(\Swift_Mailer $mailer, RouterInterface $routerInterface, TwigEngine $engine)
    {
        $this->mailer = $mailer;
        $this->router = $routerInterface;
        $this->templating = $engine;
    }

    public function sendNotification($event, Notification $notification)
    {
        $this->notification = $notification;
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
        $url = $this->router->generate("zgh_fe.post.display", ["id" => $user->getId(), "post_id" => $this->notification->getContent()["obj_id"]], true);
        $title = "{$this->notification->getContent()["user"]} has commented on your post.";
        $body = "{$this->notification->getContent()["user"]} has commented on your post - {$url}";
        $template = $this->getSingleBtnTemplate([ "notification_title" => $title, "notification_body" => $body ]);
        $this->send($title, $user->getEmail(), $template);
    }

    protected function sendLikeNotification(NotifyLikeEvent $event)
    {
        $user = $event->getUserToNotify();
        $notification = $event->getNotification();
        $url = $this->router->generate("zgh_fe.post.display", ["id" => $user->getId(), "post_id" => $this->notification->getContent()["obj_id"]], true);
        $title = "{$this->notification->getContent()["user"]} has liked your post";
        $body = "{$this->notification->getContent()["user"]} has liked your post - {$url}";
        $template = $this->getSingleBtnTemplate([ "notification_title" => $title, "notification_body" => $body ]);
        $this->send($title, $user->getEmail(), $template);
    }

    protected function sendFollowNotification(NotifyFollowEvent $event)
    {
        $user = $event->getUserToNotify();
        $notification = $event->getNotification();
        $url = $this->router->generate("zgh_fe.user_profile.index", ["id" => $this->notification->getContent()["follower_id"]], true);
        $title = "{$this->notification->getContent()["user"]} has followed you.";
        $body = sprintf("<a href='%s'>{$this->notification->getContent()["user"]}</a> has followed you.", $url);
        $template = $this->getSingleBtnTemplate([ "notification_title" => $title, "notification_body" => $body ]);
        $this->send($title, $user->getEmail(), $template);
    }

    protected function sendFollowRequestNotification(NotifyFollowRequestEvent $event)
    {
        $user = $event->getUserToNotify();
        $notification = $event->getNotification();
        $url = $this->router->generate("zgh_fe.user_profile.index", ["id" => $this->notification->getContent()["follower_id"]], true);
        $acceptUrl = $this->router->generate("zgh_fe.user.accept_follow", [
            "id" => $this->notification->getActionId(),
            "n_id" => $this->notification->getId()
        ], true);
        $denyUrl = $this->router->generate("zgh_fe.user.deny_follow", [
            "id" => $this->notification->getActionId(),
            "n_id" => $this->notification->getId()
        ], true);
        $title = "{$this->notification->getContent()["user"]} wants to follow you.";
        $body = sprintf("<a href='%s'>{$this->notification->getContent()["user"]}</a> wants to follow you.", $url);
        $template = $this->getTwoBtnTemplate([
            "notification_title" => $title,
            "notification_body" => $body,
            "accept_url" => $acceptUrl,
            "deny_url" => $denyUrl
        ]);
        $this->send($title, $user->getEmail(), $template);
    }

    protected function sendRelationshipRequest(NotifyRelationshipRequestEvent $event)
    {
        $user = $event->getUserToNotify();
        $notification = $event->getNotification();
        $url = $this->router->generate("zgh_fe.user_profile.index", ["id" => $this->notification->getContent()["requester_id"]], true);
        $acceptUrl = $this->router->generate("zgh_fe.about.accept_relationship", [
            "id" => $this->notification->getActionId(),
            "n_id" => $this->notification->getId()
        ], true);
        $denyUrl = $this->router->generate("zgh_fe.about.deny_relationship", [
            "id" => $this->notification->getActionId(),
            "n_id" => $this->notification->getId()
        ], true);
        $body = "{$this->notification->getContent()["user"]} wants to be {$this->notification->getContent()["status"]} to you.";
        $template = $this->getTwoBtnTemplate([
            "notification_title" => $body,
            "notification_body" => $body,
            "accept_url" => $acceptUrl,
            "deny_url" => $denyUrl
        ]);
        $this->send($body , $user->getEmail(), $template);
    }

    protected function send($notification_title, $email, $template)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($notification_title)
            ->setFrom("noreply@zaghruta.com")
            ->setTo($email)
            ->setBody($template, 'text/html');

        try {
            $this->mailer->send($message);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function sendEmailChangeConfirmation(User $user, $conf_email)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject("Email change confirmation")
            ->setFrom("noreply@zaghruta.com")
            ->setTo($user->getNewEmail())
            ->setBody(
                $this->templating->render("@ZghFE/Default/Emails/email_change_confirmation.txt.twig", [
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

    private function getSingleBtnTemplate(array $params = [])
    {
        return $this->getTemplate("@ZghFE/Default/Emails/single_btn_email.txt.twig", $params);
    }

    private function getTwoBtnTemplate(array $params = [])
    {
        return $this->getTemplate("@ZghFE/Default/Emails/two_btn_email.txt.twig", $params);
    }

    private function getTemplate($template, $params)
    {
        return $this->templating->render($template, $params);
    }
}