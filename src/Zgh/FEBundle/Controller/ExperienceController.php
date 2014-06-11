<?php
namespace Zgh\FEBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    public function getContentAction($id, $exp_id)
    {
        $experience = $this->getDoctrine()->getRepository("ZghFEBundle:Experience")->find($exp_id);
        return $this->render("@ZghFE/Default/experience_content.html.twig", array(
            "experience" => $experience
        ));
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