<?php
namespace Zgh\FEBundle\Model\Event;

use Symfony\Component\EventDispatcher\Event;
use Zgh\FEBundle\Entity\FollowUsers;
use Zgh\FEBundle\Entity\Like;
use Zgh\FEBundle\Entity\Notification;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Service\NotificationManager;

class NotifyFollowRequestEvent extends Event
{
    protected $content;

    /**
     * @var User
     */
    protected $user;

    protected $follower;

    protected $action_id;

    protected $type;

    /**
     * @var Notification
     */
    protected $notification;

    public function __construct(FollowUsers $follow_obj)
    {
        $follower = $follow_obj->getFollower();
        $followee = $follow_obj->getFollowee();
        $this->type = NotifyEvents::NOTIFY_FOLLOW_REQUEST;

        $this->content = [
            "user" => $follower->getFullName(),
            "follower_id" => $follower->getId(),
        ];
        $this->user = $followee;
        $this->follower = $follow_obj->getFollower();
        $this->action_id = $follow_obj->getId();
    }

    public function getNotification()
    {
        $manager = new NotificationManager();
        $notification = $manager->create($this->user, $this->content, $this->action_id, $this->type);
        return $notification;
    }

    public function getUserToNotify()
    {
        return $this->user;
    }


    public function getFollower()
    {
        return $this->follower;
    }

    public function getType()
    {
        return $this->type;
    }
}