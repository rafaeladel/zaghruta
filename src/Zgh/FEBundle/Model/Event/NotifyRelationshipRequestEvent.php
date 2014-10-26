<?php
namespace Zgh\FEBundle\Model\Event;

use Symfony\Component\EventDispatcher\Event;
use Zgh\FEBundle\Entity\FollowUsers;
use Zgh\FEBundle\Entity\Like;
use Zgh\FEBundle\Entity\Notification;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Entity\UserInfo;
use Zgh\FEBundle\Service\NotificationManager;

class NotifyRelationshipRequestEvent extends Event
{
    protected $content;

    /**
     * @var User
     */
    protected $user;

    protected $requester;

    protected $action_id;

    protected $type;

    /**
     * @var Notification
     */
    protected $notification;

    public function __construct(UserInfo $userInfo)
    {
        $requester = $userInfo->getUser();
        $receiver = $userInfo->getRelationshipUser();
        $gender = $requester->getUserInfo()->getGender() == "0" ? "his" : "her";
        $this->type = NotifyEvents::NOTIFY_RELATIONSHIP_REQUEST;

        $this->content = [
            "user" => $requester->getFullName(),
            "requester_id" => $requester->getId(),
            "requester_gender" => $gender,
            "status" => $userInfo->getStatus()
        ];
        $this->user = $receiver;
        $this->requester = $requester;
        $this->action_id = $userInfo->getId();
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

    public function getRequester()
    {
        return $this->requester;
    }

    public function getType()
    {
        return $this->type;
    }
}