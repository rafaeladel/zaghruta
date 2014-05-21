<?php
namespace Zgh\FEBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Zgh\FEBundle\Entity\FollowUsers;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\TwigExtension\FollowCheckExtension;

class FollowManager
{
    protected $em;
    protected $security_context;
    protected $follow_check;
    protected $router;

    public function __construct(
        EntityManagerInterface $entityManagerInterface,
        SecurityContextInterface $context,
        FollowCheckExtension $follow_check,
        RouterInterface $router
    ){
        $this->em = $entityManagerInterface;
        $this->security_context = $context;
        $this->follow_check = $follow_check;
        $this->router = $router;
    }

    public function follownize(User $user)
    {
        $current_user = $this->security_context->getToken()->getUser();
        $follow_obj = $this->follow_check->checkFollow($current_user, $user);

        if($follow_obj == null)
        {
            $follow_obj = new FollowUsers();

            $follower = $this->em->getRepository("ZghFEBundle:User")->find($current_user);
            $followee = $this->em->getRepository("ZghFEBundle:User")->find($user);

            $follow_obj->setFollower($follower);
            $follow_obj->setFollowee($followee);

            $this->em->persist($follow_obj);
            $this->em->flush();
            return new JsonResponse(array("msg" => 'Unfollow'));

        } else {
            $this->em->remove($follow_obj);
            $this->em->flush();
            return new JsonResponse(array("msg" => "Follow"));

        }
    }
}