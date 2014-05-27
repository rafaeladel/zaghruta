<?php
namespace Zgh\FEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Zgh\FEBundle\Entity\UserInfo;
use Zgh\FEBundle\Form\UserInfoType;
use Zgh\FEBundle\Form\VendorInfoType;


class AboutController extends Controller
{
   public function getCustomerEditAction($id)
   {
       $user_info = $this->getDoctrine()->getRepository("ZghFEBundle:UserInfo")->find($id);
       $form = $this->createForm(new UserInfoType(), $user_info);
       return $this->render("@ZghFE/Partial/about/user_profile_about_edit_customer.html.twig", array(
               "info" => $user_info,
               "form" => $form->createView()
           ));
   }

   public function postCustomerEditAction(Request $request, $id)
   {
        $user_info = $this->getDoctrine()->getRepository("ZghFEBundle:UserInfo")->find($id);
        $form = $this->createForm(new UserInfoType(), $user_info);
        $form->handleRequest($request);

       if(!$form->isValid())
       {
           return new JsonResponse(array(
                   "status" => 500,
                   "view" => $this->renderView("@ZghFE/Partial/about/user_profile_about_edit_customer.html.twig", array(
                               "info" => $user_info,
                               "form" => $form->createView()
                           ))
               ));
       }


       $this->getDoctrine()->getManager()->persist($user_info);
       $this->getDoctrine()->getManager()->flush();

       return new JsonResponse(array("status" => 200));
   }

    public function getVendorEditAction($id)
    {
        $vendor_info = $this->getDoctrine()->getRepository("ZghFEBundle:VendorInfo")->find($id);
        $form = $this->createForm(new VendorInfoType(), $vendor_info);
        return $this->render("@ZghFE/Partial/about/user_profile_about_edit_vendor.html.twig", array(
                "info" => $vendor_info,
                "form" => $form->createView()
            ));
    }

    public function postVendorEditAction(Request $request, $id)
    {
        $vendor_info = $this->getDoctrine()->getRepository("ZghFEBundle:VendorInfo")->find($id);
        $form = $this->createForm(new VendorInfoType(), $vendor_info);
        $form->handleRequest($request);

        if(!$form->isValid())
        {
            return new JsonResponse(array(
                "status" => 500,
                "view" => $this->renderView("@ZghFE/Partial/about/user_profile_about_edit_vendor.html.twig", array(
                            "info" => $vendor_info,
                            "form" => $form->createView()
                        ))
            ));
        }


        $this->getDoctrine()->getManager()->persist($vendor_info);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(array("status" => 200));
    }
}