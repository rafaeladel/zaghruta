<?php
namespace Zgh\MsgBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use FOS\MessageBundle\Event\FOSMessageEvents;
use FOS\MessageBundle\Event\ThreadEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Zgh\MsgBundle\Entity\DeletedMessage;
use Zgh\MsgBundle\Entity\Message;

class ZghMsgSubscriber implements EventSubscriberInterface
{
    /**
     * @var SecurityContextInterface
     */
    protected $context;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $em, SecurityContextInterface $contextInterface)
    {
        $this->em = $em;
        $this->context = $contextInterface;
    }

    public static function getSubscribedEvents()
    {
        return [
            FOSMessageEvents::POST_DELETE => ["onThreadDelete"]
        ];
    }

    public function onThreadDelete(ThreadEvent $event)
    {
        $thread = $event->getThread();
        $messages = $thread->getMessages();
        $currentUser = $this->context->getToken()->getUser();
        foreach($messages as $message)
        {
            $deleteRecord = new DeletedMessage();
            $deleteRecord->setMessage($message);
            $deleteRecord->setUser($currentUser);
            $this->em->persist($deleteRecord);
        }
        $this->em->flush();
    }
}