<?php
namespace Zgh\FEBundle\Model\Event;

use Symfony\Component\EventDispatcher\Event;
use Zgh\FEBundle\Entity\Comment;
use Zgh\FEBundle\Entity\Notification;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Service\NotificationManager;

class NotifyCommentEvent extends Event
{
    protected $content;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var \Zgh\FEBundle\Entity\Comment
     */
    protected $commentObj;

    protected $objectType;

    protected $action_id;

    protected $type;

    /**
     * @var Notification
     */
    protected $notification;

    public function __construct(User $user, Comment $comment)
    {
        $obj = $comment->getObject();
        $this->type = NotifyEvents::NOTIFY_COMMENT;
        $this->content = [
            "user" => $user->getFullName(),
            "obj_id" => $obj->getId(),
            "obj_type" => $comment->getObjectType()
        ];

        $this->user = $obj->getUser();
        $this->commentObj = $comment;
        $this->action_id = $comment->getId();
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

    public function getCommentObject()
    {
        return $this->commentObj;
    }

    public function getType()
    {
        return $this->type;
    }
}