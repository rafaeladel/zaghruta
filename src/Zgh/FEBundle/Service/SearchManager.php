<?php
namespace Zgh\FEBundle\Service;

use Doctrine\ORM\EntityManagerInterface;

class SearchManager
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->em = $entityManagerInterface;
    }

    public function getAllResults($query)
    {
        $q = $this->em->createQuery(
            "
                select u, pr, t, ex
                from Zgh\FEBundle\Entity\User u
                left join u.products pr
                left join u.tips t
                left join u.experiences ex
                where u.firstname like :crit
                or pr.name like :crit
                or t.title like :crit
                or ex.title like :crit
            "
        );
        $q->setParameter("crit", "%" .  strtolower($query) . "%");
        return $q->execute();
    }

    public function getPeopleResults($query)
    {
        $q = $this->em->createQuery(
            "
                select u
                from Zgh\FEBundle\Entity\User u
                where u.firstname like :crit
            "
        );
        $q->setParameter("crit", "%" .  strtolower($query) . "%");
        return $q->execute();
    }

    public function getCategoryResults($cat, $query)
    {
        $q = $this->em->createQuery("
                select c, pr
                from Zgh\FEBundle\Entity\Category c
                left join c.products pr
                where c.id = :cat
                and pr.name like :crit
            ");

        $q->setParameters([
                "cat" => $cat,
                "crit" => "%" .  strtolower($query) . "%"
            ]);

        return $q->execute();
    }
}