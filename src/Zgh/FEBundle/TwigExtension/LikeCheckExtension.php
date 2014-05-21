<?php
namespace Zgh\FEBundle\TwigExtension;

use Doctrine\ORM\EntityManagerInterface;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Model\LikeableInterface;

class LikeCheckExtension extends \Twig_Extension
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->em = $entityManagerInterface;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction("checkLike", array($this, "checkLike"))
        );
    }

    public function checkLike(User $user, LikeableInterface $criteria)
    {
        $result = $this->em->getRepository("ZghFEBundle:User")->hasLiked($user, $criteria);
        return $result;
    }

    public function getName()
    {
        return "zgh_extension_check_like";
    }
}