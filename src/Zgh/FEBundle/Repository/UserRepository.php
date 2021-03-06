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
    public function getPosts($user, $offset = null, $id_holder = null)
    {
        $sq = $this->getEntityManager()->createQueryBuilder()
                ->select("fo.id")
                ->from("Zgh\FEBundle\Entity\FollowUsers", "f")
                ->leftJoin("f.followee", "fo")
                ->where("f.follower = :follower_id")
                ->andWhere("f.is_approved = true")
                ->setParameter("follower_id" , $user);

        $ids = [];
        $result = $sq->getQuery()->getScalarResult();
        foreach($result as $item) {
            $ids[] = $item["id"];
        }
        $q = $this->getEntityManager()->createQueryBuilder();
        $orQuery = $q->expr()->orX()->add($q->expr()->eq("p.user", ":follower_id"));
        if(count($ids) > 0) {
            $orQuery->add($q->expr()->in("p.user", $ids));
        }
        $q->select("p")
            ->from("Zgh\FEBundle\Entity\Post", "p")
            ->where($orQuery)
            ->orderBy("p.created_at", "DESC")
            ->setParameter("follower_id", $user);


//
//        $q = $this->getEntityManager()->createQuery(
//            "
//                            select p from Zgh\FEBundle\Entity\Post p
//                                where p.user in (
//                                  select fo from Zgh\FEBundle\Entity\FollowUsers f
//                                    left join f.followee fo
//                                    where f.follower = :follower_id
//                                    and f.is_approved = true
//                              ) or p.user = :follower_id
//                            order by p.created_at desc
//                          "
//        )
//            ->setParameter("follower_id", $user);

        if($id_holder != null) {
            $q->andWhere("p.id < :idh")
                ->setParameter("idh", $id_holder+1);
        }

        $q->setMaxResults(6);
        if($offset != null) {
            $q->setFirstResult($offset);
        }



        return $q->getQuery()->getResult();
    }


    public function getPublicPosts($user, $offset = null, $id_holder = null)
    {
        $rsm = new ResultSetMapping;
        $rsm->addEntityResult('Zgh\FEBundle\Entity\PostsFeeds', 'post');
        $rsm->addFieldResult('post', 'id', 'id');
        $rsm->addFieldResult('post', 'user_id', 'user_id');
        $rsm->addFieldResult('post', 'fullname', 'fullname');
        $rsm->addFieldResult('post', 'created_at', 'created_at');
        $rsm->addFieldResult('post', 'updated_at', 'updated_at');
        $rsm->addFieldResult('post', 'video', 'video');
        $rsm->addFieldResult('post', 'content', 'content');
        $rsm->addFieldResult('post', 'image_name', 'image_name');

        $query = $this->_em->createNativeQuery("
                SELECT
                posts.id,
                posts.user_id,
                CONCAT(fos_user.firstname, ' ', IFNULL(fos_user.lastname,'')) fullname,
                posts.created_at,
                posts.updated_at,
                posts.video,
                posts.content,
                posts.image_name
                FROM posts join follow_users
                on posts.user_id = follow_users.followee_id
                and is_approved =1
                join fos_user
                on posts.user_id = fos_user.id
                where follow_users.follower_id = 2
                order by posts.created_at desc
        ", $rsm);
        //$query->setParameter(1, $user.id);

        return $query->getResult();

    }

    public function getUsersForRelationship($user)
    {
        $q = $this->createQueryBuilder("u");
        $q->innerJoin("u.user_info", "u_i")
            ->where("u.id != ?1")
            ->andWhere("u_i.relationship_user is NULL")
            ->andWhere(
                $q->expr()->orX(
                    $q->expr()->eq("u_i.status", "?2"),
                    $q->expr()->isNull("u_i.status")
                )
            );
        $q->setParameters([
                1 => $user,
                2 => 'Single'
            ]);
        return $q;
    }

    public function getOtherUsers($user)
    {
        $q = $this->createQueryBuilder("u");
        $q->where("u.id != :user")
            ->setParameter("user", $user);

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
            ->setParameters(
                [
                    "user" => $user,
                    "obj_id" => $criteria->getObjectId(),
                    "obj_type" => $criteria->getObjectType()
                ]
            );

        try {
            $q = $q->getQuery()->getSingleResult();
            $result = $q->getLikes()[0];
        } catch (NoResultException $e) {
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

        try {
            $result = $q->getSingleResult()->getAlbums()[0];
        } catch (NoResultException $e) {
            return false;
        }

        return $result;
    }


    public function getAllNotifications($user)
    {
        $q = $this->getEntityManager()->createQuery(
            "
                select u, n from Zgh\FEBundle\Entity\User u
                left join u.notifications n
                where u.id = :user
                order by n.created_at DESC
            "
        );
        $q->setParameter("user", $user);
        return $q->execute();
    }
}