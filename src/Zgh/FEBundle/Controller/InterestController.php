<?php

namespace Zgh\FEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Zgh\FEBundle\Form\InterestType;

class InterestController extends Controller
{
    public function getIndexAction(Request $request)
    {
        $user = $this->getUser();
        $user->setShowInterestNotification(false);
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();


        if($request->isXmlHttpRequest())
        {
            return new JsonResponse(array("status" => 200));
        }
        else
        {
            $form = $this->createForm(new InterestType(), $user);
            return $this->render("@ZghFE/Default/interests.html.twig", array("form" => $form->createView()));
        }
    }

    public function setShowNotificationOffAction()
    {
        $user = $this->getUser();
        $user->setShowInterestNotification(false);
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse(array("status" => 200));
    }

    public function postInterestAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(new InterestType(), $user);
        $form->handleRequest($request);
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($this->generateUrl("zgh_fe.user_profile.index", array("id" => $user->getId() )));
    }
}
