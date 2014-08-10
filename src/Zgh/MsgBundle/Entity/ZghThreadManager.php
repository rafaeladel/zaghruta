<?php
namespace Zgh\MsgBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\MessageBundle\EntityManager\ThreadManager;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\ReadableInterface;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\ModelManager\Builder;

class ZghThreadManager extends ThreadManager
{
    public function getAllThreadsQueryBuilder(ParticipantInterface $participant)
    {
        return $this->repository->createQueryBuilder('t')
            ->innerJoin('t.metadata', 'tm')
            ->innerJoin('tm.participant', 'p')

            // the participant is in the thread participants
            ->andWhere('p.id = :user_id')
            ->setParameter('user_id', $participant->getId())

            // the thread does not contain spam or flood
            ->andWhere('t.isSpam = :isSpam')
            ->setParameter('isSpam', false, \PDO::PARAM_BOOL)

            // the thread is not deleted by this participant
            ->andWhere('tm.isDeleted = :isDeleted')
            ->setParameter('isDeleted', false, \PDO::PARAM_BOOL)

            // sort by date of last message
            ->innerJoin('t.messages', 'm')
            ->orderBy('m.createdAt', 'DESC')
            ;
    }

    public function findParticipantAllThreads(ParticipantInterface $participant)
    {
        return $this->getAllThreadsQueryBuilder($participant)
                    ->getQuery()
                    ->execute();
    }

    public function getUnreadThreadMessagesCount(ParticipantInterface $participant, ThreadInterface $thread)
    {
        return $this->repository->createQueryBuilder('t')
            ->innerJoin('t.metadata', 'tm')
            ->innerJoin('tm.participant', 'p')
            ->innerJoin("t.messages", "m")
            ->innerJoin("m.metadata", "mm")

            ->andWhere("t.id = :thread_id")
            ->setParameter("thread_id", $thread)

            // the participant is in the thread participants
            ->andWhere('p.id = :user_id')
            ->setParameter('user_id', $participant->getId())

            // the thread does not contain spam or flood
            ->andWhere('t.isSpam = :isSpam')
            ->setParameter('isSpam', false, \PDO::PARAM_BOOL)

            // the thread is not deleted by this participant
            ->andWhere('tm.isDeleted = :isDeleted')
            ->setParameter('isDeleted', false, \PDO::PARAM_BOOL)

            // there is at least one message written by an other participant
            ->andWhere('tm.lastMessageDate IS NOT NULL')

            ->andWhere("mm.isRead = :is_read")
            ->setParameter("is_read", false)

            ;
    }

    public function findUnreadThreadMessagesCount(ParticipantInterface $participant, ThreadInterface $thread)
    {
        $results = $this->getUnreadThreadMessagesCount($participant, $thread)
            ->getQuery()
            ->execute()
            ;

        return $results;
    }
}