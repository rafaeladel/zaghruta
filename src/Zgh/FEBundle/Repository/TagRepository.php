<?php
namespace Zgh\FEBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class TagRepository extends EntityRepository
{
    public function findSearchResult($criteria, $user)
    {
        $q = $this->createQueryBuilder("t")
                ->select("t")
                ->innerJoin("t.users", "u", Join::WITH, "u.id = :user")
                ->where("t.name LIKE :name")
                ->setParameter("user", $user)
                ->setParameter("name", "%".$criteria."%")
                ->orderBy("t.name", "ASC")
                ->getQuery();
        return $q->getResult();
    }
}