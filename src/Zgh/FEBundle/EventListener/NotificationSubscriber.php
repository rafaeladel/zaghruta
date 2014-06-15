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
use Zgh\FEBundle\Service\EmailNotifier;

class NotificationSubscriber implements EventSubscriberInterface
{
    protected $em;

    /**
     * @var EmailNotifier
     */
    protected $emailNotifier;

    public function __construct(EntityManagerInterface $entityManagerInterface, EmailNotifier $emailNotifier)
    {
        $this->em = $entityManagerInterface;
        $this->emailNotifier = $emailNotifier;
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


        $notification = $event->getNotification();
        $user->addNotification($notification);
        $this->em->persist($user);
        $this->em->flush();

        if ($user->getEmailNotification()) {
            $this->emailNotifier->sendNotification($event);
        }
    }

    public function onNotifyComment(NotifyCommentEvent $event)
    {
        $notification = $event->getNotification();
        $user = $event->getUserToNotify();
        $user->addNotification($notification);
        $this->em->persist($user);
        $this->em->flush();

        if ($user->getEmailNotification()) {
            $this->emailNotifier->sendNotification($event);
        }
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