<?php
namespace Zgh\FEBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Zgh\FEBundle\Entity\Comment;
use Zgh\FEBundle\Model\CommentableInterface;
use Zgh\FEBundle\Model\Event\NotifyCommentEvent;
use Zgh\FEBundle\Model\Event\NotifyDeleteEvent;
use Zgh\FEBundle\Model\Event\NotifyEvents;

class CommentManager
{
    protected $em;
    protected $security_context;
    protected $router;
    protected $kernel;
    protected $dispatcher;

    public function __construct(EntityManager $entityManager, SecurityContextInterface $contextInterface, RouterInterface $routerInterface, Kernel $kernel, EventDispatcherInterface $eventDispatcherInterface)
    {
        $this->em = $entityManager;
        $this->security_context = $contextInterface;
        $this->router = $routerInterface;
        $this->kernel = $kernel;
        $this->dispatcher = $eventDispatcherInterface;
    }

    /**
     * @TODO: replace $entity with Commentable Interface
     *
     * @param Request $request
     * @param $entity
     * @return JsonResponse
     */
    public function postComment(Request $request, CommentableInterface $entity)
    {
        $content = $request->request->get("comment_content", null);

        if ($content == null) {
            return new RedirectResponse($this->router->generate(("zgh_fe.wall.index")));
        }

        $user = $this->security_context->getToken()->getUser();
        $comment = new Comment();
        $comment->setUser($user);
        $comment->setContent($content);
        $comment->setObject($entity);
        $entity->addComment($comment);
        $this->em->persist($comment);


        if ($user->getId() != $entity->getUser()->getId()) {
            $comment_event = new NotifyCommentEvent($user, $comment);
            $this->dispatcher->dispatch(NotifyEvents::NOTIFY_COMMENT, $comment_event);
        }
        $this->em->flush();

        return new JsonResponse(array(
            "deleteUrl" => $this->router->generate("zgh_fe.comment.delete", array("id" => $comment->getId())),
            "author" => $comment->getUser()->getFullName(),
            "author_url" => $this->router->generate("zgh_fe.user_profile.index", ["id" => $user->getId()]),
            "time" => $comment->getCreatedAt()->format("D d Y - h:m A"),
            "comments_count" => count($entity->getComments()),
            "author_pp" => in_array("ROLE_FACEBOOK", $user->getRoles()) ?
                    'https://graph.facebook.com/' . $user->getFacebookId() . '/picture' :
                    "/".$user->getProfilePhoto()->getThumbWebPath()
        ));
    }

    public function deleteComment(Comment $comment)
    {
        if ($this->security_context->isGranted("DELETE", $comment)) {
            $current_user = $this->security_context->getToken()->getUser();
            $object = $comment->getObject();
            $comment->setIsRemoved(true);
            $comment->setNotification(null);
            $this->em->persist($comment);
            $this->em->flush();

            $notification_delete_event = new NotifyDeleteEvent($object->getUser(), $comment->getId());
            $this->dispatcher->dispatch(NotifyEvents::NOTIFY_DELETE, $notification_delete_event);

            $count = count($object->getComments());
            return $count;
        } else {
            throw new AccessDeniedException();
        }
    }
}