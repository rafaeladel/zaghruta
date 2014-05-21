<?php
namespace Zgh\FEBundle\TwigExtension;

use Doctrine\ORM\EntityManagerInterface;
use Zgh\FEBundle\Entity\User;

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
            new \Twig_SimpleFunction("getOwnPosts", [$this, "getOwnPosts"]),
            new \Twig_SimpleFunction("getExpriences", [$this, "getExpriences"])
        ];
    }

    public function getExpriences(User $user)
    {
        $experiences = $this->em->getRepository("ZghFEBundle:Experience")->findByUser($user);
        return $experiences;
    }

    public function getName()
    {
        return "zgh_fe_widgets";
    }
}