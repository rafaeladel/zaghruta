<?php
namespace Zgh\FEBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Zgh\FEBundle\Entity\Like;
use Zgh\FEBundle\Entity\Notification;
use Zgh\FEBundle\Model\Event\NotifyDeleteEvent;
use Zgh\FEBundle\Model\Event\NotifyEvents;
use Zgh\FEBundle\Model\Event\NotifyLikeEvent;
use Zgh\FEBundle\Model\LikeableInterface;

class LikeManager
{
    protected $em;
    protected $security_context;
    protected $dispatcher;

    public function __construct(EntityManager $entityManager, SecurityContextInterface $contextInterface, EventDispatcherInterface $dispatcherInterface)
    {
        $this->em = $entityManager;
        $this->security_context = $contextInterface;
        $this->dispatcher = $dispatcherInterface;
    }

    public function getLikes()
    {

    }

    /**
     * @TODO: replace $entity with Likeable interface
     *
     * @param $entity
     * @return JsonResponse
     */
    public function postLike(LikeableInterface $entity)
    {
        $user = $this->security_context->getToken()->getUser();
        $result = $this->em->getRepository("ZghFEBundle:User")->hasLiked($user, $entity);
        $state = 0;
        if ($result != false) {
            //If user already liked the post remove it
            $this->em->remove($result);
            $entity->removeLike($result);
//
//            $notification_delete_event = new NotifyDeleteEvent($entity->getUser(), $result->getId());
//            $this->dispatcher->dispatch(NotifyEvents::NOTIFY_DELETE, $notification_delete_event);


            //for ui classes
            $state = 0;
        } else {
            //If not, like it
            $like = new Like();
            $like->setUser($user);
            $like->setObject($entity);
            $this->em->persist($like);
            $entity->addLike($like);
            $state = 1;

            //Don't send notification if user liked his own post
            if ($user->getId() != $entity->getUser()->getId()) {
                $like_event = new NotifyLikeEvent($user, $like);
                $this->dispatcher->dispatch(NotifyEvents::NOTIFY_LIKE, $like_event);
            }
        }
        $this->em->flush();
        $final = $this->em->getRepository("ZghFEBundle:Like")->findBy([
            "object_id" => $entity->getObjectId(),
            "object_type" => $entity->getObjectType()
        ]);
        $count = count($final);
        return new JsonResponse([
            "likes_count" => $count,
            "like_state" => $state
        ]);

    }
}