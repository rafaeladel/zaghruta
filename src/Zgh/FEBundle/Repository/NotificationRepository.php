<?php
namespace Zgh\FEBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Zgh\FEBundle\Entity\User;

class NotificationRepository extends EntityRepository
{
    public function getNewNotifications(User $user)
    {
        $q = $this->getEntityManager()->createQuery("
                        select n from Zgh\FEBundle\Entity\Notification n
                            where n.user = :user and n.is_read = false
                    ")->setParameter("user", $user);
        return $q->execute();
    }

    public function getRelationshipNotification($requester, $receiver)
    {
        $q = $this->getEntityManager()->createQuery("
                        select n from Zgh\FEBundle\Entity\Notification n
                            where n.user = :req
                            and n.other_end = :rec
                            and n.content like :type
                    ");
        $q->setParameters([
            "req" => $receiver,
            "rec" => $requester,
            "type" => "%notify.relationship.request%"
        ]);
        return $q->execute();
    }
}