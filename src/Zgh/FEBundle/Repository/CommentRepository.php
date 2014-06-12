<?php
namespace Zgh\FEBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Zgh\FEBundle\Entity\User;

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
}