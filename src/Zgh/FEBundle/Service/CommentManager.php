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
use Symfony\Component\Templating\Helper\CoreAssetsHelper;
use Zgh\FEBundle\Entity\Comment;
use Zgh\FEBundle\Model\CommentableInterface;
use Zgh\FEBundle\Model\Event\NotifyCommentEvent;
use Zgh\FEBundle\Model\Event\NotifyCommentOtherEvent;
use Zgh\FEBundle\Model\Event\NotifyDeleteEvent;
use Zgh\FEBundle\Model\Event\NotifyEvents;

class CommentManager
{
    protected $em;
    protected $security_context;
    protected $router;
    protected $kernel;
    protected $dispatcher;

    /**
     * @var CoreAssetsHelper
     */
    protected $assets;

    public function __construct(
        EntityManager $entityManager,
        SecurityContextInterface $contextInterface,
        RouterInterface $routerInterface,
        Kernel $kernel,
        EventDispatcherInterface $eventDispatcherInterface,
        CoreAssetsHelper $assetsHelper
    ) {
        $this->em = $entityManager;
        $this->security_context = $contextInterface;
        $this->router = $routerInterface;
        $this->kernel = $kernel;
        $this->dispatcher = $eventDispatcherInterface;
        $this->assets = $assetsHelper;
    }

    /**
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


        //Check if current user is not the parent entity owner
        if ($user->getId() != $entity->getUser()->getId()) {
            $comment_event = new NotifyCommentEvent($user, $comment);
            $this->dispatcher->dispatch(NotifyEvents::NOTIFY_COMMENT, $comment_event);
        } else {
            //if current user commented on his own post, notify other subscribed ones
            $users_to_notify = $this->em->getRepository("ZghFEBundle:Comment")->getValidCommentsAuthors($entity);
            foreach ($users_to_notify as $user_to_notify) {
                $event_other = new NotifyCommentOtherEvent($user_to_notify, $comment);
                $this->dispatcher->dispatch(NotifyEvents::NOTIFY_COMMENT_OTHER, $event_other);
            }
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
                    $this->assets->getUrl($user->getProfilePhoto()->getThumbWebPath())
        ));
    }

    public function deleteComment(Comment $comment)
    {
        $current_user = $this->security_context->getToken()->getUser();
        $comment_author = $comment->getUser();
        $parent_object = $comment->getObject();
        $parent_user = $parent_object->getUser();

        if ($current_user->getId() == $comment_author->getId() || $current_user->getId() == $parent_user->getId()) {
            $comment->setIsRemoved(true);
            $comment->setNotification(null);
            $this->em->persist($comment);

            $notification_delete_event = new NotifyDeleteEvent($parent_object->getUser(), $comment->getId());
            $this->dispatcher->dispatch(NotifyEvents::NOTIFY_DELETE, $notification_delete_event);

            $this->em->flush();
            $count = count($parent_object->getComments());

            return $count;
        } else {
            throw new AccessDeniedException();
        }
    }
}