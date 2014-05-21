<?php
namespace Zgh\FEBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class FollowRepository extends EntityRepository
{
    public function checkFollow($follower_id, $followee_id)
    {
        $q = $this->createQueryBuilder("f");
        $q = $q->select("f")
                ->where("f.follower = :follower_id")
                ->setParameter("follower_id", $follower_id)
                ->andWhere("f.followee = :followee_id")
                ->setParameter("followee_id", $followee_id)
                ->setMaxResults(1)
                ->getQuery();

        $result = null;
        try{
            $result = $q->getSingleResult();
            return $result;
        } catch(NoResultException $e)
        {
            return null;
        }
    }
}