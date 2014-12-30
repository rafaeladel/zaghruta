<?php

namespace Zgh\MsgBundle\FormHandler;

use FOS\MessageBundle\FormModel\AbstractMessage;
use FOS\MessageBundle\FormModel\NewThreadMessage;
use Zgh\FEBundle\Model\Event\NotifyEvents;
use Zgh\FEBundle\Model\Event\NotifyMessageEvent;

class ZghNewThreadMessageFormHandler extends ZghAbstractMessageFormHandler
{
    /**
     * Composes a message from the form data
     *
     * @param AbstractMessage $message
     * @return MessageInterface the composed message ready to be sent
     * @throws InvalidArgumentException if the message is not a NewThreadMessage
     */
    public function composeMessage(AbstractMessage $message)
    {
        if (!$message instanceof NewThreadMessage) {
            throw new \InvalidArgumentException(sprintf('Message must be a NewThreadMessage instance, "%s" given', get_class($message)));
        }

        $new_message = $this->composer->newThread()
            ->setSubject($message->getSubject())
            ->addRecipient($message->getRecipient())
            ->setSender($this->getAuthenticatedParticipant())
            ->setBody($message->getBody())
            ->getMessage();


        $msg_event = new NotifyMessageEvent($message->getRecipient(), $message, $new_message);

        $this->dispatcher->dispatch(NotifyEvents::NOTIFY_MESSAGE, $msg_event);

        return $new_message;
    }
}
