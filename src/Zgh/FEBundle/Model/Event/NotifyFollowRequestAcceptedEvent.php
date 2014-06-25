<?php
namespace Zgh\FEBundle\Model\Event;

use Symfony\Component\EventDispatcher\Event;
use Zgh\FEBundle\Entity\FollowUsers;
use Zgh\FEBundle\Entity\Like;
use Zgh\FEBundle\Entity\Notification;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Service\NotificationManager;

class NotifyFollowRequestAcceptedEvent extends Event
{
    protected $content;

    /**
     * @var User
     */
    protected $user;

    protected $followee;

    protected $action_id;

    /**
     * @var Notification
     */
    protected $notification;

    public function __construct(FollowUsers $follow_obj)
    {
        $follower = $follow_obj->getFollower();
        $followee = $follow_obj->getFollowee();

        $this->content = [
            "type" => NotifyEvents::NOTIFY_FOLLOW_REQUEST_ACCEPTED,
            "user" => $followee->getFullName(),
            "followee_id" => $followee->getId(),
        ];
        $this->user = $follower;
        $this->followee = $follow_obj->getFollowee();
        $this->action_id = $follow_obj->getId();
    }

    public function getNotification()
    {
        $manager = new NotificationManager();
        $notification = $manager->create($this->user, $this->content, $this->action_id);
        return $notification;
    }

    public function getUserToNotify()
    {
        return $this->user;
    }

    public function getFollowee()
    {
        return $this->followee;
    }
}