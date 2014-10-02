<?php

namespace Zgh\MsgBundle\Controller;

use FOS\MessageBundle\Model\ThreadInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\MessageBundle\Provider\ProviderInterface;

class MessageController extends ContainerAware
{
    /**
     * Displays the authenticated participant inbox
     *
     * @return Response
     */
    public function inboxAction(Request $request)
    {
        $container = $this->container;
        $threads = $container->get("fos_message.provider")->getRelatedThreads();
        $new_form = $container->get('fos_message.new_thread_form.factory')->create();
        $formHandler = $container->get('zgh_message.thread_form_handler');

        if($request->getMethod() == "POST") {
            $recipient = $request->request->all()["message"]["recipient"];
            $sender = $container->get("security.context")->getToken()->getUser();
            $thread = $container->get("fos_message.thread_manager")->findOneThreadByRecipient($sender, $recipient);
            if ($thread) {
                $new_form = $container->get('fos_message.reply_form.factory')->create($thread);
                $formHandler = $container->get('zgh_message.message_form_handler');
            }
            if ($message = $formHandler->process($new_form)) {
                return new RedirectResponse($this->container->get("router")->generate("fos_message_inbox"));
            }
        }

        return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:inbox.html.twig', array(
                "threads" => $threads,
                'new_form' => $new_form->createView()
            ));
    }

    public function inboxContentAction()
    {
        $container = $this->container;
        $threads = $container->get("fos_message.provider")->getRelatedThreads();

        return $this->container->get('templating')->renderResponse('ZghMsgBundle:Message:inbox_content.html.twig', array(
                'threads' => $threads,
            ));
    }

    /**
     * Displays a thread, also allows to reply to it
     *
     * @param string $threadId the thread id
     *
     * @return Response
     */
    public function threadAction($threadId)
    {
        $container = $this->container;
        $thread = $container->get("fos_message.provider")->getThread($threadId);
        $reply_form = $container->get('fos_message.reply_form.factory')->create($thread);
        $formHandler = $container->get('fos_message.reply_form.handler');

        if ($message = $formHandler->process($reply_form)) {
            return new RedirectResponse($this->container->get("router")->generate("fos_message_inbox"));
        }

        //todo!
        return $this->container->get('templating')->renderResponse('@ZghMsg/Message/thread.html.twig', array(
                'reply_form' => $reply_form->createView(),
                'thread' => $thread
            ));
    }

    public function messageListAction($threadId)
    {
        $container = $this->container;
        $thread = $container->get("fos_message.provider")->getThread($threadId);
        return $this->container->get('templating')->renderResponse('@ZghMsg/Message/message_list.html.twig', array(
                'thread' => $thread
            ));
    }

    /**
     * Deletes a thread
     *
     * @param string $threadId the thread id
     *
     * @return RedirectResponse
     */
    public function deleteAction($threadId)
    {
        $container = $this->container;
        $thread = $container->get("fos_message.provider")->getThread($threadId);
        $container->get('fos_message.deleter')->markAsDeleted($thread);
        $container->get('fos_message.thread_manager')->saveThread($thread);

        return new RedirectResponse($this->container->get('router')->generate('fos_message_inbox'));
    }
}
