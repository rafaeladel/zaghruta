<?php
namespace Zgh\FEBundle\EventListener;

use Symfony\Bundle\FrameworkBundle\Controller\ControllerResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Zgh\FEBundle\Controller\DefaultController;
use Zgh\FEBundle\Controller\NotificationController;
use Zgh\FEBundle\Controller\RegistrationController;
use Zgh\FEBundle\Controller\ResettingController;
use Zgh\FEBundle\Controller\SecurityController;
use Zgh\FEBundle\Controller\UserProfileController;

class KernelControllerListenerHandler
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    protected $resolver;

    public function __construct(ContainerInterface $containerInterface, ControllerResolver $resolver)
    {
        $this->container = $containerInterface;
        $this->resolver = $resolver;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $target_controller = $event->getController();
        if (!$target_controller[0] instanceof SecurityController
            && !$target_controller[0] instanceof RegistrationController
            && !$target_controller[0] instanceof ResettingController
            && $target_controller[1] != "getShortcutsAndNotificationAction"
        )
        {
            if ($event->isMasterRequest() && $event->getRequest()->getMethod() == "GET") {
                $context = $this->container->get("security.context");
                if ($context->getToken() && $context->isGranted("ROLE_USER") && !$context->isGranted("ROLE_FACEBOOK")) {
                    $current_user = $context->getToken()->getUser();
                    if ($current_user->getFirstTime()) {
                        $request = new Request();
                        $request->attributes->set('_controller', 'ZghFEBundle:UserProfile:getUserIntro');
                        $event->setController($this->resolver->getController($request));
                    }
                }
            }

        }

    }
}