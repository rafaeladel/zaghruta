<?php
namespace Zgh\FEBundle\EventListener;

use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Zgh\FEBundle\Service\AsyncEventDispatcher;

class AsyncEventListener
{
    protected $dispatcher;

    public function __construct(AsyncEventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function onKernelTerminate(PostResponseEvent $event)
    {
        $this->dispatcher->dispatchAsync();
    }
}