<?php
namespace Zgh\FEBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Zgh\FEBundle\Entity\Branch;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Form\BranchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class BranchController extends Controller
{
    public function getBranchesListAction(User $user)
    {
        $branches = $user->getBranches();
        return $this->render("@ZghFE/Partial/branches/user_profile_branches_content.html.twig", [
                "user" => $user,
                "branches" => $branches
            ]);
    }

    /**
     * @Security("has_role('ROLE_VENDOR')")
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function postNewBranchAction(Request $request, User $user)
    {
        $branch = new Branch();
        $form = $this->createForm(new BranchType(), $branch);

        $form->handleRequest($request);

        if(!$form->isValid())
        {
            return new JsonResponse([
                "status" => 500,
                "view" => $this->renderView("@ZghFE/Partial/branches/user_profile_branch_add.html.twig", [
                            "user" => $user,
                            "form" => $form->createView()
                        ])
            ]);
        }

        $user->addBranch($branch);

        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush($user);

        return new JsonResponse(["status" => 200]);
    }

    /**
     * @Security("has_role('ROLE_VENDOR')")
     * @ParamConverter("branch", class="ZghFEBundle:Branch", options={"id" = "branch_id"})
     * @param User $user
     * @param Branch $branch
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getEditBranchAction(User $user, Branch $branch)
    {
        $form = $this->createForm(new BranchType(), $branch);
        return $this->render("@ZghFE/Partial/branches/user_profile_branch_edit.html.twig", [
                    "user" => $user,
                    "branch" => $branch,
                    "form" => $form->createView()
                ]);
    }

    /**
     * @Security("has_role('ROLE_VENDOR')")
     * @ParamConverter("branch", class="ZghFEBundle:Branch", options={"id" = "branch_id"})
     * @param Request $request
     * @param User $user
     * @param Branch $branch
     * @return JsonResponse
     */
    public function postEditBranchAction(Request $request, User $user, Branch $branch)
    {
        $form = $this->createForm(new BranchType(), $branch);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $this->getDoctrine()->getManager()->persist($branch);
            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse(["status" => 200]);
        }

        return new JsonResponse([
            "status" => 500,
            "view" => $this->renderView("@ZghFE/Partial/branches/user_profile_branch_edit.html.twig", [
                        "user" => $user,
                        "branch" => $branch,
                        "form" => $form->createView()
                    ]),
            "errors" => $form->getErrorsAsString()
        ]);
    }

    /**
     * @ParamConverter("branch", class="ZghFEBundle:Branch", options={"id" = "branch_id"})
     */
    public function getBranchInnerAction(User $user, Branch $branch)
    {
        return $this->render("@ZghFE/Partial/branches/user_profile_branch_inner_content.html.twig", [
                "user" => $user,
                "branch" => $branch
            ]);
    }

    /**
     * @Security("has_role('ROLE_VENDOR')")
     * @ParamConverter("branch", class="ZghFEBundle:Branch", options={"id" = "branch_id"})
     */
    public function postDeleteBranchAction(User $user, Branch $branch)
    {
        $this->get("zgh_fe.delete_manager")->delete($branch);
        return $this->redirect($this->generateUrl("zgh_fe.user_profile.branches_partial", [
            "id" => $user->getId()
        ]));
    }
}