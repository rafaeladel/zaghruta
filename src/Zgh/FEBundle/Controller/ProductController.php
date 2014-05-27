<?php
namespace Zgh\FEBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Zgh\FEBundle\Entity\Product;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Form\ProductType;

class ProductController extends Controller
{
    public function getNewAction(User $user)
    {
        $authorized = $this->get("zgh_fe.user_privacy.manager")->isVisitable($user);
        if(!$authorized)
        {
            return $this->redirect($this->generateUrl("zgh_fe.user_profile.index", array("id" => $user->getId())));
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

    /**
     * @ParamConverter("product", class="ZghFEBundle:Product", options={"id" = "product_id"})
     * @param User $user
     * @param Product $product
     * @return JsonResponse
     */
    public function postDeleteAction(User $user, Product $product)
    {
        $deleter = $this->get("zgh_fe.delete_manager");
        $deleter->delete($product);
        return new JsonResponse(["status" => "200"]);
    }

    /**
     * @ParamConverter("product", class="ZghFEBundle:Product", options={"id" = "product_id"})
     * @param User $user
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getProductContentAction(User $user, Product $product)
    {
        return $this->render("@ZghFE/Default/product_content.html.twig",[
                "user" => $user,
                "product" => $product
            ]);
    }
}