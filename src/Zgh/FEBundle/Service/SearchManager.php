<?php
namespace Zgh\FEBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Join;

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
                or u.lastname like :crit
                or concat(u.firstname, ' ', u.lastname) like :crit
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
                order by pr.created_at DESC
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
                order by t.created_at DESC
            "
        );
        $q->setParameter("crit", "%" .  strtolower($query) . "%");
        return $q->execute();
    }

    public function getExperiencesResults($query, $user = null)
    {
        /** @var $q QueryBuilder */
        $q = $this->em->createQueryBuilder();
        /** @var $sq QueryBuilder */
        $sq = $this->em->createQueryBuilder();

        $orQuery = $q->expr()->orX($q->expr()->eq("u.is_private", ":priv"));
        if($user != null) {
            $sq = $sq->select("followee.id")
                        ->from("Zgh\FEBundle\Entity\User", "u")
                        ->innerJoin("u.followers", "uf")
                        ->innerJoin("uf.followee", "followee")
                        ->where("u.id = :user")
                        ->andWhere("uf.is_approved = :approved")
                        ->groupBy("followee.id")
                        ->setParameter("user", $user)
                        ->setParameter("approved", true);
            $orQuery->add($q->expr()->eq("u.id", ":user"));

            $ids = [];
            $result = $sq->getQuery()->getScalarResult();
            foreach($result as $item) {
                $ids[] = $item["id"];
            }
            if(count($ids) > 0) {
                $orQuery->add($q->expr()->in("u.id", $ids));
            }
        }

        $q->select("e")
            ->from("Zgh\FEBundle\Entity\Experience", "e")
            ->innerJoin("e.user", "u", Join::WITH, $orQuery)
            ->where("e.title LIKE :title")
            ->orderBy("e.created_at", "DESC");

        $q->setParameters([
            "priv" => false,
            "title" => "%" .  strtolower($query) . "%"
        ]);

        if($user != null) {
            $q->setParameter("user", $user);
        }

        return $q->getQuery()->execute();
    }

    public function getPeopleResults($query)
    {
        $q = $this->em->createQuery(
            "
                select u
                from Zgh\FEBundle\Entity\User u
                where (u.firstname like :crit
                or u.lastname like :crit
                or concat(u.firstname, ' ', u.lastname) like :crit)
                and u.roles like '%ROLE_CUSTOMER%'
                order by u.created_at DESC
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
                where (u.firstname like :crit
                or u.lastname like :crit
                or concat(u.firstname, ' ', u.lastname) like :crit)
                and u.roles like '%ROLE_VENDOR%'
                order by u.created_at DESC
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
                inner join pr.user v
                inner join v.vendor_info v_i
                inner join v_i.categories c
                where c.name_slug = :cat
                and pr.name like :crit
                order by pr.created_at DESC
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
                order by c.name desc
            ");
        $q->setParameters([
                "user" => $user,
            ]);

        return $q->execute();
    }

    public function getTagsByProductResults($user)
    {
        $q = $this->em->createQuery("
    select t
    from Zgh\FEBundle\Entity\Tag t
    where t.id IN (
                select DISTINCT ta.tag_id
                from Zgh\FEBundle\Entity\Product pr
                inner join pr.tags ta
                where pr.user= :user
    )
            ");
        $q->setParameters([
                "user" => $user,
            ]);

        return $q->execute();
    }


    public function getTagsByProductOnlyResults($user)
    {
        $q = $this->em->createQuery("
                select t
                from Zgh\FEBundle\Entity\Tag t
                inner join t.users u
                where u.id= :user
                order by t.name desc
            ");
        $q->setParameters([
            "user" => $user,
        ]);

        return $q->execute();
    }

    public function getVendorByCategoryResults($cat_slug, $query)
    {
        $q = $this->em->createQuery("
                select v
                from Zgh\FEBundle\Entity\User v
                inner join v.vendor_info v_i
                inner join v_i.categories c
                where c.name_slug = :cat
                and v.firstname like :crit
                and v.roles like '%ROLE_VENDOR%'
                order by v.created_at DESC
            ");

        $q->setParameters([
                "cat" => $cat_slug,
                "crit" => "%" .  strtolower($query) . "%"
            ]);

        return $q->execute();
    }

    public function getExperiencesByCategory($cat_slug, $query, $user = null)
    {
        /** @var $q QueryBuilder */
        $q = $this->em->createQueryBuilder();
        /** @var $sq QueryBuilder */
        $sq = $this->em->createQueryBuilder();

        $orQuery = $q->expr()->orX($q->expr()->eq("u.is_private", ":priv"));
        if($user != null) {
            $sq = $sq->select("followee.id")
                ->from("Zgh\FEBundle\Entity\User", "u")
                ->innerJoin("u.followers", "uf")
                ->innerJoin("uf.followee", "followee")
                ->where("u.id = :user")
                ->andWhere("uf.is_approved = :approved")
                ->groupBy("followee.id")
                ->setParameter("user", $user)
                ->setParameter("approved", true);
            $orQuery->add($q->expr()->eq("u.id", ":user"));

            $ids = [];
            $result = $sq->getQuery()->getScalarResult();
            foreach($result as $item) {
                $ids[] = $item["id"];
            }
            if(count($ids) > 0) {
                $orQuery->add($q->expr()->in("u.id", $ids));
            }
        }

        $q->select("e")
            ->from("Zgh\FEBundle\Entity\Experience", "e")
            ->innerJoin("e.categories", "c")
            ->innerJoin("e.user", "u", Join::WITH, $orQuery)
            ->where("e.title LIKE :title")
            ->andWhere("c.name_slug = :cat")
            ->orderBy("e.created_at", "DESC");

        $q->setParameters([
            "priv" => false,
            "title" => "%" .  strtolower($query) . "%",
            "cat" => $cat_slug
        ]);

        if($user != null) {
            $q->setParameter("user", $user);
        }

        return $q->getQuery()->execute();
    }

    public function getTipsByCategory($cat_slug, $query)
    {
        $q = $this->em->createQuery("
                select t
                from Zgh\FEBundle\Entity\Tip t
                inner join t.categories c
                where c.name_slug = :cat
                and t.title like :crit
                order by t.created_at DESC
            ");

        $q->setParameters([
                "cat" => $cat_slug,
                "crit" => "%" .  strtolower($query) . "%"
            ]);

        return $q->execute();
    }

    public function getProductByUserAndTag($user_id, $tag_slug, $crit)
    {
        $query = "select pr
                from Zgh\FEBundle\Entity\Product pr";

        $query .= $tag_slug == "all" ? "" :  " inner join pr.tags t" ;

        $query .= " where pr.user = :user
                    and pr.name like :crit";

        $query .= $tag_slug == "all" ? "" : " and t.name_slug = :tag order by pr.created_at DESC" ;

        $q = $this->em->createQuery($query);

        $q->setParameters([
                "user" => $user_id,
                "crit" => "%" .  strtolower($crit) . "%"
            ]);


        if($tag_slug != "all")
        {
            $q->setParameter("tag", $tag_slug);
        }


        return $q->execute();
    }

}