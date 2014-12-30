<?php

namespace Zgh\FEBundle\TwigExtension;

use FOS\MessageBundle\FormFactory\NewThreadMessageFormFactory;
use FOS\MessageBundle\Provider\Provider;

class MessageUserExtension extends \Twig_Extension
{
    protected $newThreadFormFactory;
    protected $environment;

    function __construct(NewThreadMessageFormFactory $factory)
    {
        $this->newThreadFormFactory = $factory;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction("message_user_popup", [$this, "messageUserPopup"])
        ];
    }

    public function messageUserPopup($popupId, $user = null)
    {
        $new_form = $this->newThreadFormFactory->create();
        $new_form->get("recipient")->setData($user);
        return $this->environment->render('ZghMsgBundle:Message:message_popup.html.twig', array(
                'popup_id' => $popupId,
                'new_form' => $new_form->createView()
            ));
    }


    public function getName()
    {
        return "zgh_twig_message_user";
    }
}