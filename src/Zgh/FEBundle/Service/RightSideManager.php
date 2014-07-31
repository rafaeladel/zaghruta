<?php
namespace Zgh\FEBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Zgh\FEBundle\Entity\User;

class RightSideManager
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->em = $entityManagerInterface;
    }

    public function getRecommendedPeople(User $user, $limit = null)
    {
        $q = $this->em->createQuery("
                select recUser, count(recUser.id) as recUserCount
                from Zgh\FEBundle\Entity\User recUser
                    left join recUser.followees recFollowUser
                    with recFollowUser.follower in (
                        select mutual.id
                        from Zgh\FEBundle\Entity\FollowUsers currentFollowUser
                        inner join currentFollowUser.followee mutual
                        where currentFollowUser.follower = :user
                    )
                    where recUser.roles like '%ROLE_CUSTOMER%'
                    and recUser.id != :user
                group by recUser.id
                order by recUserCount desc
            ");
        if($limit)
        {
            $q->setMaxResults($limit);
        }
        $q->setParameters([
                "user" => $user
            ]);
        return $q->execute();
    }

    public function getRecommendedVendors($user, $limit = null)
    {
        $q = $this->em->createQuery(
            "
                select v, count(f) as f_count, count(v_c) as v_c_count
                from Zgh\FEBundle\Entity\User v
                left join v.followees f
                left join v.vendor_info v_i
                left join v_i.categories v_c
                with v_c in (
                  select cat.id
                  from Zgh\FEBundle\Entity\Category cat
                  inner join cat.users u
                  where u = :user
                )
                where v.id != :user
                and v.roles like '%ROLE_VENDOR%'

                group by v.id
                order by f_count,v_c_count desc
            "
        );
//        and fr.id != :user
        if($limit)
        {
            $q->setMaxResults($limit);
        }
        $q->setParameters(
            [
                "user" => $user
            ]
        );

        return $q->execute();
    }

    public function getNewVendors($user, $limit = null)
    {
        $q = $this->em->createQuery(
            "
                select v
                from Zgh\FEBundle\Entity\User v
                where v.id != :user
                and v.roles like '%ROLE_VENDOR%'
                and v.created_at > :fresh
                order by v.created_at desc
            "
        );
        if($limit)
        {
            $q->setMaxResults($limit);
        }
        $q->setParameters(
            [
                "user" => $user,
                "fresh" => new \DateTime("1 month ago")
            ]
        );

        return $q->execute();
    }


}