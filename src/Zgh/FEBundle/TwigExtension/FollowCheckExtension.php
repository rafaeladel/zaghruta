<?php
namespace Zgh\FEBundle\TwigExtension;

use Doctrine\ORM\EntityManagerInterface;

class FollowCheckExtension extends \Twig_Extension
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->em = $entityManagerInterface;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction("checkFollow", array($this, "checkFollow"))
        );
    }

    public function checkFollow($follower_id, $followee_id)
    {
        $result = $this->em->getRepository("ZghFEBundle:FollowUsers")->findOneBy([
                "follower" => $follower_id,
                "followee" => $followee_id
            ]);
//        var_dump($result);
//        die;
        return $result;
    }

    public function getName()
    {
        return "zgh_extension_check_follow";
    }
}