<?php
namespace Zgh\FEBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
    public function findSearchResult($criteria)
    {
        $q = $this->createQueryBuilder("t")
                ->select("t")
                ->where("t.name LIKE :name")
                ->setParameter("name", "%".$criteria."%")
                ->getQuery();
        return $q->getResult();
    }
}