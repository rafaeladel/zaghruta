<?php
namespace Zgh\FEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Zgh\FEBundle\Entity\Product;
use Zgh\FEBundle\Form\ProductType;

class ProductController extends Controller
{
    public function getNewAction($id)
    {
        $user = $this->getDoctrine()->getRepository("ZghFEBundle:User")->find($id);

        $authorized = $this->get("zgh_fe.user_privacy.manager")->isVisitable($user);
        if(!$authorized)
        {
            return $this->redirect($this->generateUrl("zgh_fe.user_profile.index", array("id" => $id)));
        }

        $form = $this->createForm(new ProductType(), new Product());
        return $this->render("@ZghFE/Partial/user_profile_product_add.html.twig", array(
                "user" => $user,
                "product_form" => $form->createView()
            ));
    }

    public function postNewAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository("ZghFEBundle:User")->find($id);

        $product = new Product();
        $form = $this->createForm(new ProductType(), $product);
        $form->handleRequest($request);

        $user->addProduct($product);

        if(!$form->isValid())
        {
            return new JsonResponse(
                array(
                    "status" => 500,
                    "view" => $this->renderView("@ZghFE/Partial/user_profile_product_add.html.twig", array(
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