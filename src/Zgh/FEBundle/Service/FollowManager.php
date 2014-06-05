<?php
namespace Zgh\FEBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Zgh\FEBundle\Entity\FollowUsers;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Model\Event\NotifyDeleteEvent;
use Zgh\FEBundle\Model\Event\NotifyEvents;
use Zgh\FEBundle\Model\Event\NotifyFollowEvent;
use Zgh\FEBundle\Model\Event\NotifyFollowRequestEvent;
use Zgh\FEBundle\TwigExtension\FollowCheckExtension;

class FollowManager
{
    protected $em;
    protected $security_context;
    protected $follow_check;
    protected $router;
    protected $dispatcher;

    public function __construct(
        EntityManagerInterface $entityManagerInterface,
        SecurityContextInterface $context,
        FollowCheckExtension $follow_check,
        RouterInterface $router,
        EventDispatcherInterface $dispatcherInterface
    )
    {
        $this->em = $entityManagerInterface;
        $this->security_context = $context;
        $this->follow_check = $follow_check;
        $this->router = $router;
        $this->dispatcher = $dispatcherInterface;
    }

    public function follownize(User $user)
    {
        $current_user = $this->security_context->getToken()->getUser();
        $follow_obj = $this->follow_check->checkFollow($current_user, $user);

        if ($follow_obj == null) {

            $follow_obj = new FollowUsers();

            $follower = $this->em->getRepository("ZghFEBundle:User")->find($current_user);
            $followee = $this->em->getRepository("ZghFEBundle:User")->find($user);

            $follow_obj->setFollower($follower);
            $follow_obj->setFollowee($followee);

            if ($user->getIsPrivate()) {
                $follow_obj->setIsApproved(false);

            } else {
                $follow_obj->setIsApproved(true);

            }

            $this->em->persist($follow_obj);
            $this->em->flush();

            if ($user->getIsPrivate()) {
                $notification_event = new NotifyFollowRequestEvent($follow_obj);
                $this->dispatcher->dispatch(NotifyEvents::NOTIFY_FOLLOW_REQUEST, $notification_event);
            } else {
                $follow_event = new NotifyFollowEvent($follow_obj);
                $this->dispatcher->dispatch(NotifyEvents::NOTIFY_FOLLOW, $follow_event);
            }
            return new JsonResponse(array("msg" => $follow_obj->getIsApproved() ? 'Unfollow' : "Pending"));


        } else {
            $this->em->remove($follow_obj);
            $followee = $follow_obj->getFollowee();
            $notification_delete_event = new NotifyDeleteEvent($followee, $follow_obj->getId());
            $this->dispatcher->dispatch(NotifyEvents::NOTIFY_DELETE, $notification_delete_event);

            $this->em->flush();
            return new JsonResponse(array("msg" => "Follow"));

        }
    }
}