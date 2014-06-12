<?php
namespace Zgh\FEBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zgh\FEBundle\Model\Event\NotifyCommentEvent;
use Zgh\FEBundle\Model\Event\NotifyDeleteEvent;
use Zgh\FEBundle\Model\Event\NotifyEvents;
use Zgh\FEBundle\Model\Event\NotifyFollowEvent;
use Zgh\FEBundle\Model\Event\NotifyFollowRequestEvent;
use Zgh\FEBundle\Model\Event\NotifyLikeEvent;
use Zgh\FEBundle\Model\Event\NotifyRelationshipRequestEvent;

class NotificationSubscriber implements EventSubscriberInterface
{
    protected $em;
    protected $mailer;

    public function __construct(EntityManagerInterface $entityManagerInterface, Swift_Mailer $mailer)
    {
        $this->em = $entityManagerInterface;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            NotifyEvents::NOTIFY_LIKE => ["onNotifyLike", 0],
            NotifyEvents::NOTIFY_COMMENT => ["onNotifyComment", 0],
            NotifyEvents::NOTIFY_FOLLOW => ["onNotifyFollow", 0],
            NotifyEvents::NOTIFY_FOLLOW_REQUEST => ["onNotifyFollowRequest", 0],
            NotifyEvents::NOTIFY_RELATIONSHIP_REQUEST => ["onNotifyRelationshipRequest", 0],
            NotifyEvents::NOTIFY_DELETE => ["onNotifyDelete", 0]
        ];
    }

    public function onNotifyLike(NotifyLikeEvent $event)
    {
        $user = $event->getUserToNotify();

//        if ($target_user->getEmailNotification()) {
//            $message = \Swift_Message::newInstance()
//                        ->setSubject("Notification from zagh")
//                        ->setFrom("qwe@ewqe.com")
//                        ->setTo($target_user->getEmail())
//                        ->setBody("tesswt")
//            ;
//            $this->mailer->send($message);
//        }
        $notification = $event->getNotification();
        $user->addNotification($notification);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function onNotifyComment(NotifyCommentEvent $event)
    {
        $notification = $event->getNotification();
        $user = $event->getUserToNotify();
        $user->addNotification($notification);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function onNotifyFollow(NotifyFollowEvent $event)
    {
        $notification = $event->getNotification();
        $user = $event->getUserToNotify();
        $user->addNotification($notification);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function onNotifyFollowRequest(NotifyFollowRequestEvent $event)
    {
        $notification = $event->getNotification();
        $user = $event->getUserToNotify();
        $user->addNotification($notification);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function onNotifyRelationshipRequest(NotifyRelationshipRequestEvent $event)
    {
        $notification = $event->getNotification();
        $user = $event->getUserToNotify();
        $user->addNotification($notification);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function onNotifyDelete(NotifyDeleteEvent $event)
    {
        $notification = $this->em->getRepository("ZghFEBundle:Notification")->findOneBy(
            [
                "user" => $event->getUser(),
                "action_id" => $event->getActionId()
            ]
        );
        if ($notification != null) {
            $this->em->remove($notification);
            $this->em->flush();
        }
    }

}