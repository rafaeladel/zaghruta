<?php
namespace Zgh\FEBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zgh\FEBundle\Entity\Experience;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Form\ExperienceType;

class ExperienceController extends Controller
{
    public function getListAction($id)
    {
        $user = $this->getDoctrine()->getRepository("ZghFEBundle:User")->find($id);
        $experiences = $user->getExperiences();
        return $this->render("@ZghFE/Partial/experiences/user_profile_experience_content.html.twig", array(
            "experiences" => $experiences,
            "user" => $user
        ));
    }

    public function getContentAction(User $user, $exp_id)
    {
        $experience = $this->getDoctrine()->getRepository("ZghFEBundle:Experience")->find($exp_id);
        return $this->render("@ZghFE/Default/experience_content.html.twig", array(
            "user" => $user,
            "experience" => $experience
        ));
    }


    /**
     * @ParamConverter("experience", class="ZghFEBundle:Experience", options={"id" = "exp_id"})
     */
    public function getSingleContentAction(User $user, Experience $experience)
    {
        return $this->render("@ZghFE/Partial/experiences/user_profile_single_experience_content.html.twig",[
                "user" => $user,
                "experience" => $experience
            ]);
    }

    /**
     * @Security("has_role('ROLE_CUSTOMER')")
     *
     * @ParamConverter("experience", class="ZghFEBundle:Experience", options={"id" = "exp_id"})
     */
    public function getEditAction(User $user, Experience $experience)
    {
        if($experience->getUser()->getId() != $this->getUser()->getId())
        {
            throw new AccessDeniedException;
        }
        $experience_form = $this->createForm(new ExperienceType(), $experience, ["type" => "edit"]);
        return $this->render("@ZghFE/Partial/experiences/user_profile_experience_edit_widget.html.twig", [
                "user" => $user,
                "experience" => $experience,
                "experience_form" => $experience_form->createView()
            ]);
    }

    /**
     * @Security("has_role('ROLE_CUSTOMER')")
     * @ParamConverter("experience", class="ZghFEBundle:Experience", options={"id" = "exp_id"})
     */
    public function postEditAction(Request $request, User $user, Experience $experience)
    {
        $experience_form = $this->createForm(new ExperienceType(), $experience, ["type" => "edit"]);
        $experience_form->handleRequest($request);
        if(!$experience_form->isValid())
        {
            return new JsonResponse([
                "status" => 500,
                "view" => $this->renderView("@ZghFE/Partial/experiences/user_profile_experience_edit_widget.html.twig", [
                            "user" => $user,
                            "experience" => $experience,
                            "experience_form" => $experience_form->createView()
                        ]),
                "errors" => $experience_form->getErrorsAsString()
            ]);
        }
        $this->getDoctrine()->getManager()->persist($experience);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse(["status" => 200]);
    }


    /**
     * @ParamConverter("experience", class="ZghFEBundle:Experience", options={"id" = "exp_id"})
     */
    public function deleteAction(User $user, Experience $experience)
    {
        $this->get("zgh_fe.delete_manager")->delete($experience);
        return $this->redirect($this->generateUrl("zgh_fe.user_profile.experiences_partial",[
                "id" => $user->getId()
        ]));
    }


    /**
     * @Security("has_role('ROLE_CUSTOMER')")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function getAddContentAction($id)
    {
        $curr_user = $this->get("security.context")->getToken()->getUser();
        $user = $this->getDoctrine()->getRepository("ZghFEBundle:User")->find($id);

        if($curr_user->getId() != $user->getId())
        {
            return $this->redirect($this->generateUrl("zgh_fe.user_profile.experiences_partial",array("id" => $user->getId())));
        }
        $form = $this->createForm(new ExperienceType(), new Experience());
        return $this->render("@ZghFE/Partial/experiences/user_profile_experience_add.html.twig", array(
                "user" => $user,
                "form" => $form->createView()
            ));

    }

    /**
     * @Security("has_role('ROLE_CUSTOMER')")
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function postNewAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository("ZghFEBundle:User")->find($id);

        $experience = new Experience();
        $form = $this->createForm(new ExperienceType(), $experience);
        $form->handleRequest($request);
        $user->addExperience($experience);

        if(!$form->isValid())
        {
            return new JsonResponse(
                array(
                    "status" => 500,
                    "view" => $this->renderView("@ZghFE/Partial/experiences/user_profile_experience_add.html.twig", array(
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