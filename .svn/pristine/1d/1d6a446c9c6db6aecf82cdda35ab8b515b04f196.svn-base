<?php
namespace Zgh\FEBundle\Model\Event;

use Symfony\Component\EventDispatcher\Event;
use Zgh\FEBundle\Entity\Like;
use Zgh\FEBundle\Entity\Notification;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Service\NotificationManager;

class NotifyLikeEvent extends Event
{
    protected $content;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var \Zgh\FEBundle\Entity\Like
     */
    protected $likeObj;

    protected $objectType;

    protected $action_id;

    /**
     * @var Notification
     */
    protected $notification;

    public function __construct(User $user, Like $like)
    {
        $obj = $like->getObject();

        $this->content = [
            "type" => NotifyEvents::NOTIFY_LIKE,
            "user" => $user->getFullName(),
            "obj_id" => $obj->getId(),
            "obj_type" => $like->getObjectType()
        ];

        $this->user = $obj->getUser();
        $this->likeObj = $like;
        $this->action_id = $like->getId();
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

    public function getLikeObject()
    {
        return $this->likeObj;
    }
}