<?php
namespace Zgh\FEBundle\Model\Event;

use Symfony\Component\EventDispatcher\Event;
use Zgh\FEBundle\Entity\Like;
use Zgh\FEBundle\Entity\Notification;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Service\NotificationManager;

class NotifyDeleteEvent extends Event
{
    /**
     * @var
     */
    protected $action_id;

    protected $user;

    public function __construct(User $user, $action_id)
    {
        $this->user = $user;
        $this->action_id = $action_id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getActionId()
    {
        return $this->action_id;
    }
}