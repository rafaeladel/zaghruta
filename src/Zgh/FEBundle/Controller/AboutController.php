<?php
namespace Zgh\FEBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zgh\FEBundle\Entity\Notification;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Entity\UserInfo;
use Zgh\FEBundle\Form\UserInfoType;
use Zgh\FEBundle\Form\VendorInfoType;
use Zgh\FEBundle\Model\Event\NotifyEvents;
use Zgh\FEBundle\Model\Event\NotifyRelationshipRequestEvent;


class AboutController extends Controller
{
    /**
     * @Security("has_role('ROLE_CUSTOMER')")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getCustomerEditAction($id)
    {
        $user_info = $this->getDoctrine()->getRepository("ZghFEBundle:UserInfo")->find($id);
        $form = $this->createForm(new UserInfoType(), $user_info);
        return $this->render("@ZghFE/Partial/about/user_profile_about_edit_customer.html.twig", array(
            "info" => $user_info,
            "form" => $form->createView()
        ));
    }

    /**
     * @Security("has_role('ROLE_CUSTOMER')")
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function postCustomerEditAction(Request $request, $id)
    {
        $user_info = $this->getDoctrine()->getRepository("ZghFEBundle:UserInfo")->find($id);
        $old_user_info = $this->getDoctrine()->getRepository("ZghFEBundle:UserInfo")->find($id);
        $form = $this->createForm(new UserInfoType(), $user_info);
        $form->handleRequest($request);
        if (!$form->isValid()) {
            return new JsonResponse(array(
                "status" => 500,
                "view" => $this->renderView("@ZghFE/Partial/about/user_profile_about_edit_customer.html.twig", array(
                        "info" => $user_info,
                        "form" => $form->createView()
                    ))
            ));
        }


        if ($user_info->getStatus() != null) {

            $target_user = $old_user_info->getRelationshipUser() instanceof User ? $user_info->getRelationshipUser() : null;
            $target_user_info = $target_user instanceof User ? $target_user->getUserInfo() : null;
            var_dump($target_user);
            var_dump($target_user_info);
            if ($user_info->getStatus() == "Single") {
                if ($target_user_info instanceof UserInfo ) {
                    //Setting the other side relationship info
                    $target_user_info->setStatus("Single");
                    $target_user_info->setRelationshipUser(null);
                    $this->getDoctrine()->getManager()->persist($target_user_info);
                }
                $user_info->setRelationshipUser(null);
            } else {
                $user_info->setRelationshipAccepted(false);
                $rel_event = new NotifyRelationshipRequestEvent($user_info);
                $this->container->get("event_dispatcher")->dispatch(
                    NotifyEvents::NOTIFY_RELATIONSHIP_REQUEST,
                    $rel_event
                );

//                        $old_user_info = $this->resetUserInfo($entity);
//                        if($entity->getRelationshipUser() instanceof User){
//                            $target_user_info = $this->setUserInfoData($entity->getRelationshipUser()->getUserInfo(), $entity->getStatus(), $entity->getUser());
//                        }
            }

        }

        $this->getDoctrine()->getManager()->persist($user_info);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(array("status" => 200));
    }

    /**
     * @ParamConverter("notification", class="ZghFEBundle:Notification", options={"id" = "n_id"})
     * @param UserInfo $userInfo
     * @param Notification $notification
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function acceptRelationshipAction(UserInfo $userInfo, Notification $notification)
    {
        if($this->getUser()->getId() != $userInfo->getRelationshipUser()->getId())
        {
            throw new AccessDeniedException();
        }
        $reciever = $this->getUser();
        $reciever_userInfo = $reciever->getUserInfo();
        $reciever_userInfo->setStatus($userInfo->getStatus());
        $reciever_userInfo->setRelationshipUser($userInfo->getUser());
        $reciever_userInfo->setRelationshipAccepted(true);

        $userInfo->setRelationshipAccepted(true);

        $this->getDoctrine()->getManager()->persist($userInfo);
        $this->getDoctrine()->getManager()->persist($reciever_userInfo);
        $this->getDoctrine()->getManager()->remove($notification);
        $this->getDoctrine()->getManager()->flush();
        $requester_id = $userInfo->getUser()->getId();
        return $this->redirect($this->generateUrl("zgh_fe.user_profile.index", ["id"=> $requester_id]));
    }

    /**
     * @ParamConverter("notification", class="ZghFEBundle:Notification", options={"id" = "n_id"})
     * @param UserInfo $userInfo
     * @param Notification $notification
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function denyRelationshipAction(UserInfo $userInfo, Notification $notification)
    {
        if($this->getUser()->getId() != $userInfo->getRelationshipUser()->getId())
        {
            throw new AccessDeniedException();
        }
        $reciever = $this->getUser();
        $userInfo->setRelationshipUser(null);
        $userInfo->setStatus(null);
        $this->getDoctrine()->getManager()->persist($userInfo);
        $this->getDoctrine()->getManager()->remove($notification);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($this->generateUrl("zgh_fe.notification.get_list"));
    }

    /**
     * @Security("has_role('ROLE_VENDOR')")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getVendorEditAction($id)
    {
        $vendor_info = $this->getDoctrine()->getRepository("ZghFEBundle:VendorInfo")->find($id);
        $form = $this->createForm(new VendorInfoType(), $vendor_info);
        return $this->render("@ZghFE/Partial/about/user_profile_about_edit_vendor.html.twig", array(
            "info" => $vendor_info,
            "form" => $form->createView()
        ));
    }

    /**
     * @Security("has_role('ROLE_VENDOR')")
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function postVendorEditAction(Request $request, $id)
    {
        $vendor_info = $this->getDoctrine()->getRepository("ZghFEBundle:VendorInfo")->find($id);
        $form = $this->createForm(new VendorInfoType(), $vendor_info);
        $form->handleRequest($request);

        if (!$form->isValid()) {
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