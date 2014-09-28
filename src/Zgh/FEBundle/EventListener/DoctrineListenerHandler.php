<?php
namespace Zgh\FEBundle\EventListener;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Acl\Dbal\AclProvider;
use Symfony\Component\Security\Acl\Dbal\MutableAclProvider;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Model\AclProviderInterface;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Zgh\FEBundle\Entity\Category;
use Zgh\FEBundle\Entity\Comment;
use Zgh\FEBundle\Entity\Like;
use Zgh\FEBundle\Entity\Post;
use Zgh\FEBundle\Entity\Tag;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Entity\UserInfo;
use Zgh\FEBundle\Entity\VendorInfo;
use Zgh\FEBundle\Model\CommentableInterface;
use Zgh\FEBundle\Model\Event\NotifyEvents;
use Zgh\FEBundle\Model\Event\NotifyRelationshipRequestEvent;
use Zgh\FEBundle\Model\LikeableInterface;

class DoctrineListenerHandler implements EventSubscriber
{
    /**
     * @var \Doctrine\ORM\UnitOfWork
     */
    private $uow;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    private $container;

    public function __construct(Container $container)
    {
        //Due to some circular reference error
        $this->container = $container;
    }

    public function getSubscribedEvents()
    {
        return array(
            "postLoad",
            "prePersist",
            "postPersist",
            "onFlush",
            "postRemove"
        );
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->em = $args->getEntityManager();
        if ($entity instanceof Comment) {
            $aclProvider = $this->container->get("security.acl.provider");
            $objectIdentity = ObjectIdentity::fromDomainObject($entity);
            $acl = $aclProvider->createAcl($objectIdentity);

            // retrieving the security identity of the currently logged-in user
            $user = $this->container->get("security.context")->getToken()->getUser();
            $securityIdentity = UserSecurityIdentity::fromAccount($user);

            // grant owner access
            $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
            $aclProvider->updateAcl($acl);
        }
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->em = $args->getEntityManager();

        if ($entity instanceof Like) {
            $parent = $this->em->getRepository(Like::getTypes()[$entity->getObjectType()])->find(
                $entity->getObjectId()
            );
            $entity->setObject($parent);
        }

        if ($entity instanceof LikeableInterface) {
            $likes = $this->em->getRepository("ZghFEBundle:Like")->findBy(
                [
                    "object_id" => $entity->getObjectId(),
                    "object_type" => $entity->getObjectType()
                ]
            );

            $entity->setLikes(new ArrayCollection($likes));
        }

        if ($entity instanceof Comment) {
            $parent = $this->em->getRepository(Comment::getTypes()[$entity->getObjectType()])->find(
                $entity->getObjectId()
            );
            $entity->setObject($parent);
        }

        if ($entity instanceof CommentableInterface) {
            $comments = $this->em->getRepository("ZghFEBundle:Comment")->findBy(
                [
                    "object_id" => $entity->getObjectId(),
                    "object_type" => $entity->getObjectType()
                ]
            );
            $entity->setComments(new ArrayCollection($comments));
        }
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->em = $args->getEntityManager();

        if($entity instanceof Tag || $entity instanceof Category)
        {
            $slugifier = $this->container->get("zgh_fe.slugifier");
            $entity->setNameSlug($slugifier->slugify($entity->getName()));

        }
    }


    /**
     * @TODO    Fix resetting old entity relationship
     * @param   OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $this->em = $args->getEntityManager();
        $this->uow = $this->em->getUnitOfWork();
        $updates = $this->uow->getScheduledEntityUpdates();


        foreach ($updates as $entity) {

            if ($entity instanceof VendorInfo) {
                $user = $this->em->getRepository("ZghFEBundle:User")->find($entity->getUser()->getId());
                $user->setFirstname($entity->getCompanyName());
                $user = $this->em->merge($user);
                $this->persistAssoc($user);
            }

            //Likes
            if ($entity instanceof Like) {
                $obj = $entity->getObject();
                $likes = $this->em->getRepository("ZghFEBundle:Like")->findBy(
                    [
                        "object_id" => $entity->getObjectId(),
                        "object_type" => $entity->getObjectType()
                    ]
                );
                $obj->setLikes(new ArrayCollection($likes));
            }


        }
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $entity = $args->getEntity();

        //Removing related likes
        if ($entity instanceof LikeableInterface) {
            $likes = $entity->getLikes();
            foreach ($likes as $like) {
                $em->remove($like);
            }
            $em->flush();
        }

        //Removing related comments
        if ($entity instanceof CommentableInterface) {
            $comments = $entity->getComments();
            foreach ($comments as $comment) {
                $em->remove($comment);
            }
            $em->flush();
        }
    }


    private function persistAssoc($obj)
    {
        if ($obj != null) {
            $this->em->persist($obj);
            $this->uow->computeChangeSet($this->em->getClassMetadata(get_class($obj)), $obj);
        }
    }
}