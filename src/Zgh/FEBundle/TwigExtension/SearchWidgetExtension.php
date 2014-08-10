<?php
namespace Zgh\FEBundle\TwigExtension;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Twig_Environment;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Service\SearchManager;

class SearchWidgetExtension extends \Twig_Extension
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    /**
     * @var \Zgh\FEBundle\Service\SearchManager
     */
    protected $searchManager;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var Twig_Environment
     */
    protected $env;

    public function initRuntime(Twig_Environment $environment)
    {
        $this->env = $environment;
    }


    public function __construct(EntityManagerInterface $entityManagerInterface, SearchManager $searchManager, RouterInterface $routerInterface) {
        $this->em = $entityManagerInterface;
        $this->searchManager = $searchManager;
        $this->router = $routerInterface;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction("renderSearch", [$this, "renderSearch"]),
        ];
    }

    public function renderSearch($user = null, $inProduct = false)
    {
        $custome_class = "";
        $url = "";
        if($inProduct == true) {
            $tags = $this->searchManager->getTagsByProductResults($user);
            $url = $this->router->generate("zgh_fe.products.search", ["id" => $user->getId()]);
            return $this->env->render("@ZghFE/Partial/search/search_products.html.twig", [
                    "tags" => $tags,
                    "search_url" => $url
                ]);

        } else {
            $categories = $this->em->getRepository("ZghFEBundle:Category")->findAllAsc();
            $url = $this->router->generate("zgh_fe.search.start_search");
            return $this->env->render("@ZghFE/Partial/search/search_widget.html.twig", [
                "in_product" => $inProduct,
                "categories" => $categories,
                "custome_class" => $custome_class,
                "search_url" => $url
            ]);
        }

    }

    public function getName()
    {
        return "zgh_extension_render_search";
    }
}