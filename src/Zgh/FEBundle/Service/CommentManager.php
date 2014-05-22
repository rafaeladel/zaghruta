<?php
namespace Zgh\FEBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Zgh\FEBundle\Entity\Comment;
use Zgh\FEBundle\Model\CommentableInterface;

class CommentManager
{
    protected $em;
    protected $security_context;
    protected $router;
    public function __construct(EntityManager $entityManager, SecurityContextInterface $contextInterface, RouterInterface $routerInterface)
    {
        $this->em = $entityManager;
        $this->security_context = $contextInterface;
        $this->router = $routerInterface;
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

        if($content == null)
        {
            return new RedirectResponse($this->router->generate(("zgh_fe.wall.index")));
        }

        $user = $this->security_context->getToken()->getUser();
        $comment = new Comment();
        $comment->setUser($user);
        $comment->setContent($content);
        $comment->setObject($entity);
        $entity->addComment($comment);
        $this->em->persist($comment);
        $this->em->flush();
        return new JsonResponse(array(
            "deleteUrl" => $this->router->generate("zgh_fe.comment.delete", array("id" => $comment->getId())),
            "author" => $comment->getUser()->getFullName(),
            "author_url" => "user_url",
            "time" => $comment->getCreatedAt()->format("D - h A"),
            "comments_count" => count($entity->getComments()),
            "author_pp" => in_array("ROLE_FACEBOOK", $user->getRoles()) ?
                    'https://graph.facebook.com/'.$user->getFacebookId().'/picture' :
                    str_replace("app_dev.php", "/", $request->getUriForPath($user->getProfilePhoto()->getThumbWebPath()))
        ));
    }

    public function deleteComment(Comment $comment)
    {
        $this->em->remove($comment);
        $this->em->flush();
        $object = $comment->getObject();
        $object->removeComment($comment);
        $count = count($object->getComments());
        return new JsonResponse(array("comments_count" => $count));
    }
}