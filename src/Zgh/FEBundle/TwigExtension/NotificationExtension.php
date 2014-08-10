<?php

namespace Zgh\FEBundle\TwigExtension;

use Doctrine\ORM\EntityManagerInterface;
use FOS\MessageBundle\FormFactory\NewThreadMessageFormFactory;
use FOS\MessageBundle\Provider\Provider;
use Symfony\Component\Security\Core\SecurityContextInterface;

class NotificationExtension extends \Twig_Extension
{
    protected $environment;
    protected $security_context;
    protected $em;

    function __construct(SecurityContextInterface $securityContextInterface, EntityManagerInterface $entityManagerInterface)
    {
        $this->security_context = $securityContextInterface;
        $this->em = $entityManagerInterface;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction("notifications_count", [$this, "notificationsCount"]),
            new \Twig_SimpleFunction("notifications_widget", [$this, "notificationsWidget"])
        ];
    }

    public function notificationsCount()
    {
        $user = $this->security_context->getToken()->getUser();
        $notifications = $this->em->getRepository("ZghFEBundle:Notification")->getNewNotifications($user);
        $count = count($notifications);
        return $count;
    }


    public function getName()
    {
        return "zgh_twig_notification";
    }
}