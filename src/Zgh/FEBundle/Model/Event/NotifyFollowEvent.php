<?php
namespace Zgh\FEBundle\Model\Event;

use Symfony\Component\EventDispatcher\Event;
use Zgh\FEBundle\Entity\FollowUsers;
use Zgh\FEBundle\Entity\Like;
use Zgh\FEBundle\Entity\Notification;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Service\NotificationManager;

class NotifyFollowEvent extends Event
{
    protected $content;

    /**
     * @var User
     */
    protected $user;

    protected $follower;

    protected $action_id;

    /**
     * @var Notification
     */
    protected $notification;

    public function __construct(FollowUsers $follow)
    {
        $follower = $follow->getFollower();

        $this->content = [
            "type" => NotifyEvents::NOTIFY_FOLLOW,
            "user" => $follow->getFollower()->getFullName(),
            "follower_id" => $follower->getId(),
        ];

        $this->follower = $follow->getFollower();
        $this->user = $follow->getFollowee();
        $this->action_id = $follow->getId();
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

    public function getFollower()
    {
        return $this->follower;
    }
}