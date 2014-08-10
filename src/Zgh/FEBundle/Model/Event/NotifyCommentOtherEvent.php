<?php
namespace Zgh\FEBundle\Model\Event;

use Symfony\Component\EventDispatcher\Event;
use Zgh\FEBundle\Entity\Comment;
use Zgh\FEBundle\Entity\Notification;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Service\NotificationManager;

class NotifyCommentOtherEvent extends Event
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

    /**
     * @var Notification
     */
    protected $notification;

    public function __construct(User $user, Comment $comment)
    {
        $obj = $comment->getObject();
        $owner = $obj->getUser();

        $gender = null;
        if(in_array("ROLE_CUSTOMER", $owner->getRoles())) {
            $gender = $owner->getUserInfo()->getGender() == "0" ? "his" : "her";
        } elseif(in_array("ROLE_VENDOR", $owner->getRoles())) {
            $gender = "their";
        }

        $this->content = [
            "type" => NotifyEvents::NOTIFY_COMMENT_OTHER,
            "user" => $owner->getFullName(),
            "user_id" => $owner->getId(),
            "user_gender" => $gender,
            "obj_id" => $obj->getId(),
            "obj_type" => $comment->getObjectType()
        ];

        $this->user = $user;
        $this->commentObj = $comment;
        $this->action_id = $comment->getId();
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

    public function getCommentObject()
    {
        return $this->commentObj;
    }
}