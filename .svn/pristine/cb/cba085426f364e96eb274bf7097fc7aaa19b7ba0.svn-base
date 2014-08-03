<?php
namespace Zgh\MsgBundle\Entity;

use FOS\MessageBundle\EntityManager\MessageManager;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\ThreadInterface;

class ZghMessageManager extends MessageManager
{
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