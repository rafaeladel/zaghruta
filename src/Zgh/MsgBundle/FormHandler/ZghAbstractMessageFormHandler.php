<?php

namespace Zgh\MsgBundle\FormHandler;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use FOS\MessageBundle\Composer\ComposerInterface;
use FOS\MessageBundle\FormModel\AbstractMessage;
use FOS\MessageBundle\Security\ParticipantProviderInterface;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Sender\SenderInterface;

/**
 * Handles messages forms, from binding request to sending the message
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
abstract class ZghAbstractMessageFormHandler
{
    protected $request;
    protected $composer;
    protected $sender;
    protected $participantProvider;
    protected $dispatcher;

    public function __construct(Request $request, ComposerInterface $composer, SenderInterface $sender, ParticipantProviderInterface $participantProvider, EventDispatcherInterface $dispatcher)
    {
        $this->request = $request;
        $this->composer = $composer;
        $this->sender = $sender;
        $this->participantProvider = $participantProvider;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Processes the form with the request
     *
     * @param Form $form
     * @return Message|false the sent message if the form is bound and valid, false otherwise
     */
    public function process(Form $form)
    {
        if ('POST' !== $this->request->getMethod()) {
            return false;
        }

        $form->bind($this->request);

        return $this->processValidForm($form);
    }

    /**
     * Processes the valid form, sends the message
     *
     * @param Form $form
     * @return MessageInterface the sent message
     */
    public function processValidForm(Form $form)
    {
        $message = $this->composeMessage($form->getData());

        $this->sender->send($message);

        return $message;
    }

    /**
     * Composes a message from the form data
     *
     * @param AbstractMessage $message
     * @return MessageInterface the composed message ready to be sent
     */
    abstract protected function composeMessage(AbstractMessage $message);

    /**
     * Gets the current authenticated user
     *
     * @return ParticipantInterface
     */
    protected function getAuthenticatedParticipant()
    {
        return $this->participantProvider->getAuthenticatedParticipant();
    }
}
