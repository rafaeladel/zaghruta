<?php
namespace Zgh\FEBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Zgh\FEBundle\Entity\Experience;
use Zgh\FEBundle\Entity\Photo;
use Zgh\FEBundle\Entity\Post;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Model\LikeableInterface;

class UserRepository extends EntityRepository
{
    public function getPosts($user)
    {
        $q = $this->getEntityManager()->createQuery("
                select p from Zgh\FEBundle\Entity\Post p
                    where p.user in (
                      select fo from Zgh\FEBundle\Entity\FollowUsers f
                        left join f.followee fo
                        where f.follower = :follower_id
                  ) or p.user = :follower_id
                order by p.created_at desc
              ")
            ->setParameter("follower_id", $user);

        return $q->getResult();
    }

    public function getUsersForRelationship($user)
    {
        $q = $this->createQueryBuilder("u");
        $q->where("u.id != ?1")
            ->innerJoin("u.user_info", "u_i")
//            ->add("where", $q->expr()->orX(
//                        $q->expr()->eq("u_i.status", "?2"),
//                        $q->expr()->isNull("u_i.status")
//                    ))
            ->andWhere("u_i.status IN (?2)")
            ->setParameters(array(
                    1 => $user,
                    2 => array("Single", "NULL")
                ))
//            ->setParameters(array(
//                    1 => $user,
//                    2 => "Single"
//                ))
            ;
//        var_dump($q->getQuery()->getResult());
//        die;
        return $q;
    }

    public function hasLiked(User $user, LikeableInterface $criteria)
    {
        $q = $this->createQueryBuilder("u")
            ->select("u, l")
            ->where("u.id = :user")
            ->leftJoin("u.likes", "l")
            ->andWhere("l.object_id = :obj_id")
            ->andWhere("l.object_type = :obj_type")
            ->setParameters([
                "user" => $user,
                "obj_id" => $criteria->getObjectId(),
                "obj_type" => $criteria->getObjectType()
            ]);

        try{
           $q = $q->getQuery()->getSingleResult();
            $result = $q->getLikes()[0];
        } catch(NoResultException $e){
            return false;
        }

        return $result;
    }

    public function hasAlbum(User $user, $name)
    {
        $q = $this->createQueryBuilder("u")
                ->select("u, a")
                ->where("u.id = :user")
                ->setParameter("user", $user)
                ->leftJoin("u.albums", "a")
                ->andWhere("a.name = :name")
                ->setParameter("name", $name)
                ->setMaxResults(1)
                ->getQuery();

        try{
            $result = $q->getSingleResult()->getAlbums()[0];
        } catch(NoResultException $e){
            return false;
        }

        return $result;
    }
}