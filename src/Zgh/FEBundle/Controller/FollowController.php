<?php
namespace Zgh\FEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Zgh\FEBundle\Entity\FollowUsers;


class FollowController extends Controller
{
    public function setFollowAction(Request $request, $er_id, $ing_id)
    {
        $user = $this->getDoctrine()->getRepository("ZghFEBundle:User")->find($ing_id);
        return $this->get("zgh_fe.follow.manager")->follownize($user);
    }

}