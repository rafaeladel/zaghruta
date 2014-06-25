<?php
namespace Zgh\FEBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Model\CommentableInterface;

class CommentRepository extends EntityRepository
{
    public function getValidComments(User $user)
    {
        $q = $this->getEntityManager()->createQuery("
                select c
                from Zgh\FEBundle\Comment c
                left join c.user u
                where u.id = :user
                and c.is_approved = true
            ");

        return $q->execute();
    }

    public function getValidCommentsAuthors(CommentableInterface $entity)
    {
        $q = $this->getEntityManager()->createQuery("
                select u
                from Zgh\FEBundle\Entity\User u
                inner join u.comments c
                where u.id != :user_id
                and c.is_removed = false
                and c.object_id = :entity_id
                and c.object_type = :entity_type
            ");

        $q->setParameters([
                "user_id" => $entity->getUser()->getId(),
                "entity_id" => $entity->getObjectId(),
                "entity_type" => $entity->getObjectType()
            ]);

        return $q->execute();
    }
}