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

    protected $action_id;

    /**
     * @var Notification
     */
    protected $notification;

    public function __construct(User $user, Comment $comment)
    {
        $obj = $comment->getObject();

        $this->content = [
            "type" => Notification::TYPE_COMMENT,
            "user" => $user->getFullName(),
            "post_id" => $obj->getId()
        ];

        $this->user = $obj->getUser();
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
}