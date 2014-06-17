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
//        var_dump($q->execute());
//        die;
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

    public function getProductByCategoryResults($cat_slug, $query)
    {
        $q = $this->em->createQuery("
                select pr
                from Zgh\FEBundle\Entity\Product pr
                inner join pr.category c
                where c.name_slug = :cat
                and pr.name like :crit
            ");
        $q->setParameters([
                "cat" => $cat_slug,
                "crit" => "%" .  strtolower($query) . "%"
            ]);

        return $q->execute();
    }

    public function getCategoryByProductResults($user)
    {
        $q = $this->em->createQuery("
                select c
                from Zgh\FEBundle\Entity\Category c
                inner join c.products pr
                where pr.user = :user
            ");
        $q->setParameters([
                "user" => $user,
            ]);

        return $q->execute();
    }

    public function getVendorByCategoryResults($cat_slug, $query)
    {
        $q = $this->em->createQuery("
                select u
                from Zgh\FEBundle\Entity\User u
                inner join u.interests i
                where i.name_slug = :cat
                and u.firstname like :crit
                and u.roles like '%ROLE_VENDOR%'
            ");

        $q->setParameters([
                "cat" => $cat_slug,
                "crit" => "%" .  strtolower($query) . "%"
            ]);

        return $q->execute();
    }

    public function getExperiencesByCategory($cat_slug, $query)
    {
        $q = $this->em->createQuery("
                select e
                from Zgh\FEBundle\Entity\Experience e
                inner join e.category c
                where c.name_slug = :cat
                and e.name like :crit
            ");

        $q->setParameters([
                "cat" => $cat_slug,
                "crit" => "%" .  strtolower($query) . "%"
            ]);

        return $q->execute();
    }

    public function getProductByUserAndCategory($user_id, $cat_slug, $crit)
    {
        $query = "select pr
                from Zgh\FEBundle\Entity\Product pr";

        $query .= $cat_slug == "all" ? "" :  " inner join pr.category c" ;

        $query .= " where pr.user = :user
                    and pr.name like :crit";

        $query .= $cat_slug == "all" ? "" : " and c.name_slug = :cat" ;

        $q = $this->em->createQuery($query);

        $q->setParameters([
            "user" => $user_id,
            "crit" => "%" .  strtolower($crit) . "%"
        ]);


        if($cat_slug != "all")
        {
            $q->setParameter("cat", $cat_slug);
        }


        return $q->execute();
    }

}