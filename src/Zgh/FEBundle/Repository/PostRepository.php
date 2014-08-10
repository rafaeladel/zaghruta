<?php
namespace Zgh\FEBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository
{
    public function findAll()
    {
        $q = $this->createQueryBuilder("p");
        $q->select("p, l, c")
            ->orderBy("p.created_at", "DESC")
            ->leftJoin("p.likes", "l")
            ->leftJoin("p.comments", "c")
        ;
        return $q->getQuery()->getResult();
    }

    public function findPosts($id, $offset = null)
    {
        $q = $this->createQueryBuilder("p");
        $q->select("p");
        if($id != null)
        {
            $q->where("p.user = :id")
                ->setParameter("id", $id);
        }
        $q->orderBy("p.created_at", "DESC");
        $q->setMaxResults(6);
        if($offset != null) {
            $q->setFirstResult($offset);
        }
        return $q->getQuery()->getResult();
    }
}