<?php

namespace Zgh\MsgBundle\Provider;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\MessageBundle\Provider\Provider;

class ZghMsgProvider extends Provider
{
    public function getRelatedThreads()
    {
        $participant = $this->getAuthenticatedParticipant();
        $result = $this->threadManager->findParticipantAllThreads($participant);
        return $result;
    }

}