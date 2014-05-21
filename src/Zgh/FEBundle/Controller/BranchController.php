<?php
namespace Zgh\FEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Zgh\FEBundle\Entity\Branch;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Form\BranchType;

class BranchController extends Controller
{
    public function getBranchesListAction(User $user)
    {
        $branches = $user->getBranches();
        return $this->render("@ZghFE/Partial/user_profile_branches_content.html.twig", [
                "user" => $user,
                "branches" => $branches
            ]);
    }

    public function postNewBranchAction(Request $request, User $user)
    {
        $branch = new Branch();
        $form = $this->createForm(new BranchType(), $branch);

        $form->handleRequest($request);

        if(!$form->isValid())
        {
            return new JsonResponse([
                "status" => 500,
                "view" => $this->renderView("@ZghFE/Partial/user_profile_branch_add.html.twig", [
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
}