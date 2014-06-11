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

    public function getProductsResults($query)
    {
        $q = $this->em->createQuery(
            "
                select pr
                from Zgh\FEBundle\Entity\Product pr
                where pr.name like :crit
            "
        );
        $q->setParameter("crit", "%" .  strtolower($query) . "%");
        return $q->execute();
    }

    public function getTipsResults($query)
    {
        $q = $this->em->createQuery(
            "
                select t
                from Zgh\FEBundle\Entity\Tip t
                where t.title like :crit
            "
        );
        $q->setParameter("crit", "%" .  strtolower($query) . "%");
        return $q->execute();
    }

    public function getExperiencesResults($query)
    {
        $q = $this->em->createQuery(
            "
                select ex
                from Zgh\FEBundle\Entity\Experience ex
                where ex.title like :crit
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
                and u.roles like '%ROLE_CUSTOMER%'
            "
        );
        $q->setParameter("crit", "%" .  strtolower($query) . "%");
        return $q->execute();
    }

    public function getVendorsResults($query)
    {
        $q = $this->em->createQuery(
            "
                select u
                from Zgh\FEBundle\Entity\User u
                where u.firstname like :crit
                and u.roles like '%ROLE_VENDOR%'
            "
        );
        $q->setParameter("crit", "%" .  strtolower($query) . "%");
        return $q->execute();
    }

    public function getProductByCategoryResults($cat_id, $query)
    {
        $q = $this->em->createQuery("
                select pr
                from Zgh\FEBundle\Entity\Product pr
                inner join pr.category c
                where c.id = :cat
                and pr.name like :crit
            ");

        $q->setParameters([
                "cat" => $cat_id,
                "crit" => "%" .  strtolower($query) . "%"
            ]);

        return $q->execute();
    }

    public function getVendorByCategoryResults($cat_id, $query)
    {
        $q = $this->em->createQuery("
                select u
                from Zgh\FEBundle\Entity\User u
                inner join u.interests i
                where i.id = :cat
                and u.firstname like :crit
                and u.roles like '%ROLE_VENDOR%'
            ");

        $q->setParameters([
                "cat" => $cat_id,
                "crit" => "%" .  strtolower($query) . "%"
            ]);

        return $q->execute();
    }

    public function getExperiencesByCategory($cat_id, $query)
    {
        $q = $this->em->createQuery("
                select e
                from Zgh\FEBundle\Entity\Experience e
                inner join e.category c
                where c.id = :cat
                and e.name like :crit
            ");

        $q->setParameters([
                "cat" => $cat_id,
                "crit" => "%" .  strtolower($query) . "%"
            ]);

        return $q->execute();
    }

    public function getProductByUserAndCategory($user_id, $cat_id, $query)
    {
        $q = $this->em->createQuery("
                select pr
                from Zgh\FEBundle\Entity\Product pr
                inner join pr.category c
                where pr.user = :user
                and c.id = :cat
                and pr.name like :crit
            ");

        $q->setParameters([
            "user" => $user_id,
            "cat" => $cat_id,
            "crit" => "%" .  strtolower($query) . "%"
        ]);

        return $q->execute();
    }

}