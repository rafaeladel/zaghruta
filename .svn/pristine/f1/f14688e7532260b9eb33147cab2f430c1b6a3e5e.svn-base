<?php
namespace Zgh\FEBundle\Service;

use Zgh\FEBundle\Entity\Notification;
use Zgh\FEBundle\Entity\User;

class NotificationManager
{
    public function create(User $user, array $content, $action_id)
    {
        $notification = new Notification();
        $notification->setContent($content);
        $notification->setUser($user);
        $notification->setActionId($action_id);
        return $notification;
    }
}