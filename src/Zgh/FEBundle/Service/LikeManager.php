<?php
namespace Zgh\FEBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Zgh\FEBundle\Entity\Like;
use Zgh\FEBundle\Model\LikeableInterface;

class LikeManager
{
    protected $em;
    protected $security_context;
    public function __construct(EntityManager $entityManager, SecurityContextInterface $contextInterface)
    {
        $this->em = $entityManager;
        $this->security_context = $contextInterface;
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
        if($result != false)
        {
            //If user already liked the post remove it
            $this->em->remove($result);
            $entity->removeLike($result);
        } else {
            //If not, like it
            $like = new Like();
            $like->setUser($user);
            $like->setObject($entity);
            $this->em->persist($like);
            $entity->addLike($like);
        }
        $this->em->flush();
        $count = count($entity->getLikes());
        return new JsonResponse(array("likes_count" => $count));

    }
}