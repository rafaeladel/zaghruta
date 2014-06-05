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

    protected $action_id;

    /**
     * @var Notification
     */
    protected $notification;

    public function __construct(FollowUsers $follow)
    {

        $this->content = [
            "type" => Notification::TYPE_FOLLOW,
            "user" => $follow->getFollower()->getFullName(),
            "follower_id" => $follow->getFollower()->getId()
        ];

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
}