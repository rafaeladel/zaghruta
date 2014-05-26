<?php
namespace Zgh\FEBundle\TwigExtension;

use Doctrine\ORM\EntityManagerInterface;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Model\CommentableInterface;

class WidgetsExtension extends \Twig_Extension
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->em = $entityManagerInterface;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction("getExpriences", [$this, "getExpriences"]),
            new \Twig_SimpleFunction("getProducts", [$this, "getProducts"]),
            new \Twig_SimpleFunction("getComments", [$this, "getComments"])
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

    public function getName()
    {
        return "zgh_fe_widgets";
    }
}