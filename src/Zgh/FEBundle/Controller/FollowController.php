<?php
namespace Zgh\FEBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zgh\FEBundle\Entity\FollowUsers;
use Zgh\FEBundle\Entity\Notification;


class FollowController extends Controller
{
    public function setFollowAction(Request $request, $er_id, $ing_id)
    {
        $user = $this->getDoctrine()->getRepository("ZghFEBundle:User")->find($ing_id);
        return $this->get("zgh_fe.follow.manager")->follownize($user);
    }

    /**
     * @ParamConverter("notification", class="ZghFEBundle:Notification", options={"id" = "n_id"})
     * @param FollowUsers $follow
     * @param Notification $notification
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function acceptFollowAction(FollowUsers $follow, Notification $notification)
    {
        if($this->getUser()->getId() != $follow->getFollowee()->getId())
        {
            throw new AccessDeniedException();
        }
        $follow->setIsApproved(true);
        $this->getDoctrine()->getManager()->persist($follow);
        $this->getDoctrine()->getManager()->remove($notification);
        $this->getDoctrine()->getManager()->flush();
        $follower_id = $follow->getFollower()->getId();
        return $this->redirect($this->generateUrl("zgh_fe.user_profile.index", ["id"=> $follower_id]));
    }

    /**
     * @ParamConverter("notification", class="ZghFEBundle:Notification", options={"id" = "n_id"})
     * @param FollowUsers $follow
     * @param Notification $notification
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function denyFollowAction(FollowUsers $follow, Notification $notification)
    {
        if($this->getUser()->getId() != $follow->getFollowee()->getId())
        {
            throw new AccessDeniedException();
        }
        $this->getDoctrine()->getManager()->remove($follow);
        $this->getDoctrine()->getManager()->remove($notification);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($this->generateUrl("zgh_fe.wall.index"));
    }
}