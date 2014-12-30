<?php
namespace Zgh\FEBundle\Repository;

use Doctrine\ORM\EntityRepository;

class BasicRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array('created_at' => 'DESC'));
    }
}