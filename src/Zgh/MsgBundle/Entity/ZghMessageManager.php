<?php
namespace Zgh\MsgBundle\Entity;

use Doctrine\ORM\Query\Expr\Join;
use FOS\MessageBundle\EntityManager\MessageManager;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\ThreadInterface;

class ZghMessageManager extends MessageManager
{
    public function getThreadMessages(ParticipantInterface $participant, ThreadInterface $thread)
    {
//        $idsQuery = $this->repository->createQueryBuilder("idm")
//                ->select("idm.id")->getQuery()->getScalarResult();
//        $m_ids = array_map(function($v) { return $v["id"]; }, $idsQuery);

//        $subQuery = $this->em->getRepository("ZghMsgBundle:DeletedMessage")->createQueryBuilder("dm")->select("dm")->getQuery()->getResult();

//        $subQuery = $subQuery->select("dm")->getQuery()->getResult();
//            ->where($subQuery->expr()->in("dm.message", ":message"))
//            ->setParameter("message", $m_ids)
//            ->getQuery()
//            ->getResult();

        $builder = $this->repository->createQueryBuilder('m');

        $builder
            ->select("m")
            ->innerJoin("m.thread", "t")
            ->where("t.id = :t_id");

//        if(count($subQuery) > 0 ){
//            $ru_ids = array_map(function($record) { return $record->getUser()->getId(); }, $subQuery);
//            $rm_ids = array_map(function($record) { return $record->getMessage()->getId(); }, $subQuery);
//
//            $builder->andWhere($builder->expr()->andX(
//                $builder->expr()->notIn(":user_id", $ru_ids),
//                $builder->expr()->notIn("m.id", $rm_ids)
//
//            ))
//            ->setParameter("user_id", $participant->getId());
//        }

        $result = $builder->setParameter("t_id", $thread)
            ->getQuery()
            ->getResult();
        return $result;
    }

    public function getNbUnreadThreadMessage(ParticipantInterface $participant, ThreadInterface $thread)
    {
        $builder = $this->repository->createQueryBuilder('m');

        return (int)$builder
            ->select($builder->expr()->count('mm.id'))
            ->innerJoin("m.thread", "t")
            ->where("t.id = :t_id")
            ->setParameter("t_id", $thread)
            ->innerJoin('m.metadata', 'mm')
            ->innerJoin('mm.participant', 'p')
            ->andWhere('p.id = :participant_id')
            ->setParameter('participant_id', $participant->getId())
            ->andWhere('m.sender != :sender')
            ->setParameter('sender', $participant->getId())
            ->andWhere('mm.isRead = :isRead')
            ->setParameter('isRead', false, \PDO::PARAM_BOOL)
            ->getQuery()
            ->getSingleScalarResult();
    }
}