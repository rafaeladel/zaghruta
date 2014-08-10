<?php

namespace Zgh\FEBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Zgh\FEBundle\Form\InterestType;
use Zgh\FEBundle\Form\VendorCategoryType;

class InterestController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
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
            if(in_array("ROLE_CUSTOMER", $user->getRoles())) {
                $form = $this->createForm(new InterestType(), $user);
            } elseif(in_array("ROLE_VENDOR", $user->getRoles())) {
                $vendor_info = $user->getVendorInfo();
                $form = $this->createForm(new VendorCategoryType(), $vendor_info);
            }
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

        //for vendor registration
        $user->setFirstTime(false);

        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        if(in_array("ROLE_CUSTOMER", $user->getRoles())) {
            $form = $this->createForm(new InterestType(), $user);
        } elseif(in_array("ROLE_VENDOR", $user->getRoles())) {
            $vendor_info = $user->getVendorInfo();
            $form = $this->createForm(new VendorCategoryType(), $vendor_info);
        }

        $form->handleRequest($request);

        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($this->generateUrl("zgh_fe.user_profile.index", array("id" => $user->getId() )));
    }
}
