<?php
namespace Zgh\FEBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zgh\FEBundle\Model\Event\NotifyCommentEvent;
use Zgh\FEBundle\Model\Event\NotifyCommentOtherEvent;
use Zgh\FEBundle\Model\Event\NotifyDeleteEvent;
use Zgh\FEBundle\Model\Event\NotifyEvents;
use Zgh\FEBundle\Model\Event\NotifyFollowEvent;
use Zgh\FEBundle\Model\Event\NotifyFollowRequestAcceptedEvent;
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
            NotifyEvents::NOTIFY_LIKE => [["onNotifyLike", 10], ["onEmailNotify", 0]],
            NotifyEvents::NOTIFY_COMMENT => [["onNotifyComment", 10], ["onEmailNotify", 0]],
            NotifyEvents::NOTIFY_COMMENT_OTHER => [["onNotifyCommentOther", 10], ["onEmailNotify", 0]],
            NotifyEvents::NOTIFY_FOLLOW => [["onNotifyFollow", 10], ["onEmailNotify", 0]],
            NotifyEvents::NOTIFY_FOLLOW_REQUEST => [["onNotifyFollowRequest", 10], ["onEmailNotify", 0]],
            NotifyEvents::NOTIFY_FOLLOW_REQUEST_ACCEPTED => [["onNotifyFollowRequestAccepted", 10], ["onEmailNotify", 0]],
            NotifyEvents::NOTIFY_RELATIONSHIP_REQUEST => [["onNotifyRelationshipRequest", 10], ["onEmailNotify", 0]],
            NotifyEvents::NOTIFY_DELETE => ["onNotifyDelete", 10]
        ];
    }

    public function onNotifyLike(NotifyLikeEvent $event)
    {
        $notification = $event->getNotification();
        $user = $event->getUserToNotify();
        $notification->setLike($event->getLikeObject());
        $user->addNotification($notification);
        $notification->setOtherEnd($event->getLikeObject()->getUser());
        $this->em->persist($user);
        $this->em->flush();

    }

    public function onNotifyComment(NotifyCommentEvent $event)
    {
        $notification = $event->getNotification();
        $user = $event->getUserToNotify();
        $notification->setComment($event->getCommentObject());
        $user->addNotification($notification);
        $notification->setOtherEnd($event->getCommentObject()->getUser());
        $this->em->persist($user);
        $this->em->flush();
    }

    public function onNotifyCommentOther(NotifyCommentOtherEvent $event)
    {
        $notification = $event->getNotification();
        $user = $event->getUserToNotify();
        $notification->setComment($event->getCommentObject());
        $user->addNotification($notification);
        $notification->setOtherEnd($event->getCommentObject()->getUser());
        $this->em->persist($user);
        $this->em->flush();
    }

    public function onNotifyFollow(NotifyFollowEvent $event)
    {
        $notification = $event->getNotification();
        $user = $event->getUserToNotify();
        $notification->setOtherEnd($event->getFollower());
        $user->addNotification($notification);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function onNotifyFollowRequest(NotifyFollowRequestEvent $event)
    {
        $notification = $event->getNotification();
        $user = $event->getUserToNotify();
        $user->addNotification($notification);
        $notification->setOtherEnd($event->getFollower());
        $this->em->persist($user);
        $this->em->flush();
    }


    public function onNotifyFollowRequestAccepted(NotifyFollowRequestAcceptedEvent $event)
    {
        $notification = $event->getNotification();
        $user = $event->getUserToNotify();
        $user->addNotification($notification);
        $notification->setOtherEnd($event->getFollowee());
        $this->em->persist($user);
        $this->em->flush();
    }

    public function onNotifyRelationshipRequest(NotifyRelationshipRequestEvent $event)
    {
        $notification = $event->getNotification();
        $user = $event->getUserToNotify();
        $user->addNotification($notification);
        $notification->setOtherEnd($event->getRequester());
        $this->em->persist($user);
        $this->em->flush();
    }

    public function onNotifyDelete(NotifyDeleteEvent $event)
    {
        $notification = $this->em->getRepository("ZghFEBundle:Notification")->findOneBy(
            [
//                "user" => $event->getUser(),
                "action_id" => $event->getActionId()
            ]
        );
        if ($notification != null) {
            $this->em->remove($notification);
            $this->em->flush();
        }
    }

    public function onEmailNotify($event)
    {
        $user = $event->getUserToNotify();

        if ($user->getEmailNotification()) {
            try {
                $this->emailNotifier->sendNotification($event);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
    }
}