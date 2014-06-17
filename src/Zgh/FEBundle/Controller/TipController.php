<?php
namespace Zgh\FEBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zgh\FEBundle\Entity\Tip;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Form\TipType;


class TipController extends Controller
{
    public function getListAction($id)
    {
        $user = $this->getDoctrine()->getRepository("ZghFEBundle:User")->find($id);
        $tips = $user->getTips();
        return $this->render("@ZghFE/Partial/tips/user_profile_tip_content.html.twig", array(
                "tips" => $tips,
                "user" => $user
            ));
    }

    public function getContentAction(User $user, $tip_id)
    {
        $tip = $this->getDoctrine()->getRepository("ZghFEBundle:Tip")->find($tip_id);
        return $this->render("@ZghFE/Default/tip_content.html.twig", array(
                "user" => $user,
                "tip" => $tip
            ));
    }

    /**
     * @ParamConverter("tip", class="ZghFEBundle:Tip", options={"id" = "tip_id"})
     */
    public function getSingleContentAction(User $user, Tip $tip)
    {
        return $this->render("@ZghFE/Partial/tips/user_profile_single_tip_content.html.twig",[
                "user" => $user,
                "tip" => $tip
            ]);
    }

    /**
     * @Security("has_role('ROLE_VENDOR')")
     *
     * @ParamConverter("tip", class="ZghFEBundle:Tip", options={"id" = "tip_id"})
     */
    public function getEditAction(User $user, Tip $tip)
    {
        if($tip->getUser()->getId() != $this->getUser()->getId())
        {
            throw new AccessDeniedException;
        }
        $tip_form = $this->createForm(new TipType(), $tip, ["type" => "edit"]);
        return $this->render("@ZghFE/Partial/tips/user_profile_tip_edit_widget.html.twig", [
                "user" => $user,
                "tip" => $tip,
                "tip_form" => $tip_form->createView()
            ]);
    }

    /**
     * @Security("has_role('ROLE_VENDOR')")
     * @ParamConverter("tip", class="ZghFEBundle:Tip", options={"id" = "tip_id"})
     */
    public function postEditAction(Request $request, User $user, Tip $tip)
    {
        $tip_form = $this->createForm(new TipType(), $tip, ["type" => "edit"]);
        $tip_form->handleRequest($request);
        if(!$tip_form->isValid())
        {
            return new JsonResponse([
                "status" => 500,
                "view" => $this->renderView("@ZghFE/Partial/tips/user_profile_tip_edit_widget.html.twig", [
                            "user" => $user,
                            "tip" => $tip,
                            "tip_form" => $tip_form->createView()
                        ]),
                "errors" => $tip_form->getErrorsAsString()
            ]);
        }
        $this->getDoctrine()->getManager()->persist($tip);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse(["status" => 200]);
    }


    /**
     * @ParamConverter("tip", class="ZghFEBundle:Tip", options={"id" = "tip_id"})
     */
    public function deleteAction(User $user, Tip $tip)
    {
        $this->get("zgh_fe.delete_manager")->delete($tip);
        return $this->redirect($this->generateUrl("zgh_fe.user_profile.tips_partial",[
                    "id" => $user->getId()
                ]));
    }

    /**
     * @Security("has_role('ROLE_VENDOR')")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function getAddContentAction($id)
    {
        $curr_user = $this->get("security.context")->getToken()->getUser();
        $user = $this->getDoctrine()->getRepository("ZghFEBundle:User")->find($id);

        if($curr_user->getId() != $user->getId())
        {
            return $this->redirect($this->generateUrl("zgh_fe.user_profile.tips_partial",array("id" => $user->getId())));
        }
        $form = $this->createForm(new TipType(), new Tip());
        return $this->render("@ZghFE/Partial/tips/user_profile_tip_add.html.twig", array(
                "user" => $user,
                "form" => $form->createView()
            ));

    }

    /**
     * @Security("has_role('ROLE_VENDOR')")
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function postNewAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository("ZghFEBundle:User")->find($id);

        $tip = new Tip();
        $form = $this->createForm(new TipType(), $tip);
        $form->handleRequest($request);

        $user->addTip($tip);

        if(!$form->isValid())
        {
            return new JsonResponse(
                array(
                    "status" => 500,
                    "view" => $this->renderView("@ZghFE/Partial/tips/user_profile_tip_add.html.twig", array(
                                "user" => $user,
                                "form" => $form->createView()
                            )),
                    "errors" => $form->getErrorsAsString()
                )
            );
        }

        $em->persist($user);
        $em->flush();
        return new JsonResponse(array("status" => 200));
    }
}