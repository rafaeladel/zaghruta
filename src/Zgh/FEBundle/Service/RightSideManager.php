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

    public function getRecommendedPeople(User $user)
    {
        $q = $this->em->createQuery("
                select recUser, count(recUser.id) as recUserCount
                from Zgh\FEBundle\Entity\User recUser
                    inner join recUser.followees recFollowUser
                    where recFollowUser.follower in (
                        select mutual.id
                        from Zgh\FEBundle\Entity\FollowUsers currentFollowUser
                        inner join currentFollowUser.followee mutual
                        where currentFollowUser.follower = :user
                    )
                    and recUser.roles like '%ROLE_CUSTOMER%'
                group by recUser.id
                order by recUserCount desc
            ");

        $q->setParameters([
                "user" => $user
            ]);
        return $q->execute();
    }

    public function getRecommendedVendors($user)
    {
        $q = $this->em->createQuery(
            "
                select v, count(f) as f_count
                from Zgh\FEBundle\Entity\User v
                left join v.followees f
                left join f.follower fr
                where v.id != :user
                and v.roles like '%ROLE_VENDOR%'
                group by v.id
                order by f_count desc
            "
        );
//        and fr.id != :user

        $q->setParameters(
            [
                "user" => $user
            ]
        );

        return $q->execute();
    }

    public function getNewVendors($user)
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
        $q->setParameters(
            [
                "user" => $user,
                "fresh" => new \DateTime("1 month ago")
            ]
        );

        return $q->execute();
    }


}