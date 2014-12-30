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
        $builder = $this->repository->createQueryBuilder('m');
        $result = $builder
            ->select("m")
            ->leftJoin("m.delete_table", "dm")
            ->where("dm.user is NULL OR dm.user != :user_id")
            ->andWhere("m.thread = :t_id")
            ->setParameter("user_id", $participant->getId())
            ->setParameter("t_id", $thread)
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