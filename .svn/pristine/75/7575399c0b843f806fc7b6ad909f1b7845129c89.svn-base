<?php
namespace Zgh\FEBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function findAllAsc()
    {
        $q = $this->getEntityManager()->createQuery("
            select c
            from Zgh\FEBundle\Entity\Category c
            order by c.name asc
        ");

        return $q->execute();
    }
}