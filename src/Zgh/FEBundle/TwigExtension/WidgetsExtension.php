<?php
namespace Zgh\FEBundle\TwigExtension;

use Doctrine\ORM\EntityManagerInterface;
use Twig_Environment;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Model\CommentableInterface;

class WidgetsExtension extends \Twig_Extension
{
    protected $em;

    /**
     * @var Twig_Environment
     */
    protected $env;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->em = $entityManagerInterface;
    }

    public function initRuntime(Twig_Environment $environment)
    {
        $this->env = $environment;
    }


    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction("getExpriences", [$this, "getExpriences"]),
            new \Twig_SimpleFunction("getProducts", [$this, "getProducts"]),
            new \Twig_SimpleFunction("getComments", [$this, "getComments"]),
            new \Twig_SimpleFunction("getSearchWidget", [$this, "getSearchWidget"] )
        ];
    }

    public function getExpriences(User $user)
    {
        $experiences = $this->em->getRepository("ZghFEBundle:Experience")->findByUser($user);
        return $experiences;
    }

    public function getComments(CommentableInterface $entity)
    {
        $comments = $entity->getComments();
        return $comments;
    }

    public function getProducts(User $user)
    {
        $products = $this->em->getRepository("ZghFEBundle:Product")->findByUser($user);
        return $products;
    }

    public function getSearchWidget()
    {
        $categories = $this->em->getRepository("ZghFEBundle:Category")->findAll();
        return $this->env->render("@ZghFE/Partial/search_widget.html.twig", ["categories" => $categories]);
    }

    public function getName()
    {
        return "zgh_fe_widgets";
    }
}