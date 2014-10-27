<?php
namespace Zgh\FEBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Zgh\FEBundle\Entity\Notification;
use Zgh\FEBundle\Entity\User;

class NotificationManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }
    public function create(User $user, array $content, $action_id, $type)
    {
        $notification = new Notification();
        $notification->setContent($content);
        $notification->setUser($user);
        $notification->setActionId($action_id);
        $notification->setType($type);
        $this->em->persist($notification);
        $this->em->flush();
        return $notification;
    }
}