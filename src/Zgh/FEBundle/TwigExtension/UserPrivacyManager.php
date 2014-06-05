<?php
namespace Zgh\FEBundle\TwigExtension;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Zgh\FEBundle\Entity\User;

class UserPrivacyManager extends \Twig_Extension
{
    protected $em;
    protected $security_context;

    public function __construct(EntityManagerInterface $em, SecurityContextInterface $sec){
        $this->em = $em;
        $this->security_context = $sec;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction("isVisitable", array($this, "isVisitable"))
        );
    }

    public function isVisitable(User $user)
    {
        $current_user = $this->security_context->getToken()->getUser();
        if($user->getId() == $current_user->getId()){
            return true;
        }

        $followed = $this->em->getRepository("ZghFEBundle:FollowUsers")->checkFollow($current_user->getId() , $user->getId());

        if($user->getIsPrivate() && ( $followed == null || !$followed->getIsApproved())){
            return false;
        } else {
            return true;
        }

    }

    public function getName()
    {
        return "zgh_privacy_manager";
    }
}