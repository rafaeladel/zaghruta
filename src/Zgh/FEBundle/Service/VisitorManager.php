<?php
namespace Zgh\FEBundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class VisitorManager
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var RouterInterface
     */
    protected $router;

    public function __construct(RequestStack $requestStack, RouterInterface $routerInterface)
    {
        $this->requestStack = $requestStack;
        $this->router = $routerInterface;
    }

    public function getVisitor()
    {
        $url = $this->requestStack->getMasterRequest()->getUri();
        $needles = ["zaghruta.com/beta/zaghruta/web/", "beta.zaghruta.com/", "zaghruta/web/app_dev.php/", "app_dev.php/"];
        $path = str_replace($needles, "", parse_url($url,PHP_URL_PATH ));
        $info = $this->router->match($path);
        return $info;
    }
}