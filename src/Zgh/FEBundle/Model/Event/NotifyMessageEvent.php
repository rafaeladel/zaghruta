<?php
namespace Zgh\FEBundle\Model\Event;

use FOS\MessageBundle\FormModel\AbstractMessage;
use FOS\MessageBundle\FormModel\NewThreadMessage;
use Symfony\Component\EventDispatcher\Event;
use Zgh\FEBundle\Entity\Like;
use Zgh\FEBundle\Entity\Notification;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Service\NotificationManager;
use Zgh\MsgBundle\Entity\Message;
use Zgh\MsgBundle\Entity\Thread;

class NotifyMessageEvent extends Event
{
    protected $content;

    /**
     * @var User
     */
    protected $user;

    /**
     * Thread Id
     */
    protected $action_id;

    protected $type;

    protected $other_end;

    /**
     * @var Notification
     */
    protected $notification;

    public function __construct(User $user, NewThreadMessage $new_message, Message $message)
    {
        $this->content = [];
        $this->type = NotifyEvents::NOTIFY_MESSAGE;
        $this->other_end = $message->getSender();
        $this->user = $new_message->getRecipient();
        $this->action_id = $message->getId();
    }

    public function getNotification()
    {
        $manager = new NotificationManager();
        $notification = $manager->create($this->user, $this->content, $this->action_id, $this->type, $this->other_end);
        return $notification;
    }

    public function getUserToNotify()
    {
        return $this->user;
    }


    public function getType()
    {
        return $this->type;
    }
}