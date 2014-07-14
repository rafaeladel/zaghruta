<?php
namespace Zgh\FEBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zgh\FEBundle\Entity\Product;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Entity\Wishlist;
use Zgh\FEBundle\Form\ProductType;
use Zgh\FEBundle\Form\ProductWishlistType;
use Zgh\FEBundle\Form\WishlistType;

class ProductController extends Controller
{
    /**
     * @Security("has_role('ROLE_VENDOR')")
     *
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function getNewAction(User $user)
    {
        $authorized = $this->get("zgh_fe.user_privacy.manager")->isVisitable($user);
        if(!$authorized)
        {
            return $this->redirect($this->generateUrl("zgh_fe.user_profile.index", array("id" => $user->getId())));
        }
        $form = $this->createForm(new ProductType(), new Product(), ["type" => "add"]);
        return $this->render("@ZghFE/Partial/products/user_profile_product_add.html.twig", array(
                "user" => $user,
                "product_form" => $form->createView()
            ));
    }

    /**
     * @Security("has_role('ROLE_VENDOR')")
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function postNewAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository("ZghFEBundle:User")->find($id);

        $product = new Product();
        $form = $this->createForm(new ProductType(), $product, ["type" => "add"]);
        $form->handleRequest($request);

        $user->addProduct($product);

        if(!$form->isValid())
        {
            return new JsonResponse(
                array(
                    "status" => 500,
                    "view" => $this->renderView("@ZghFE/Partial/products/user_profile_product_add.html.twig", array(
                                "user" => $user,
                                "product_form" => $form->createView()
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
     */
    public function getProductParentContentWidgetAction(User $user, Product $product)
    {
        if($this->getUser() instanceof User) {
            $addWishlistForm = $this->createForm(new ProductWishlistType($this->get("security.context")), $product);
        }
        return $this->render("@ZghFE/Partial/products/product_content_widget.html.twig",[
                "is_popup" => true,
                "user" => $user,
                "product" => $product,
                "addWishlistForm" => $this->getUser() instanceof User ? $addWishlistForm->createView() : null
            ]);
    }

    /**
     * @ParamConverter("product", class="ZghFEBundle:Product", options={"id" = "product_id"})
     */
    public function getProductContentWidgetAction(User $user, Product $product)
    {
        if($this->getUser() instanceof User) {
            $addWishlistForm = $this->createForm(new ProductWishlistType($this->get("security.context")), $product);
        }
        return $this->render("@ZghFE/Partial/products/user_profile_product_content_widget.html.twig",[
                "user" => $user,
                "is_popup" => false,
                "product" => $product,
                "addWishlistForm" => $this->getUser() instanceof User ? $addWishlistForm->createView() : null
            ]);
    }

    /**
     * @Security("has_role('ROLE_VENDOR')")
     *
     * @ParamConverter("product", class="ZghFEBundle:Product", options={"id" = "product_id"})
     */
    public function getEditAction(User $user, Product $product)
    {
        if($product->getUser()->getId() != $this->getUser()->getId())
        {
            throw new AccessDeniedException;
        }
        $product_form = $this->createForm(new ProductType(), $product, ["type" => "edit"]);
        return $this->render("@ZghFE/Partial/products/user_profile_product_edit_widget.html.twig", [
                "user" => $user,
                "is_popup" => false,
                "product" => $product,
                "product_form" => $product_form->createView()
            ]);
    }

    /**
     * @Security("has_role('ROLE_VENDOR')")
     * @ParamConverter("product", class="ZghFEBundle:Product", options={"id" = "product_id"})
     */
    public function postEditAction(Request $request, User $user, Product $product)
    {
        $product_form = $this->createForm(new ProductType(), $product, ["type" => "edit"]);
        $product_form->handleRequest($request);
        if(!$product_form->isValid())
        {
            return new JsonResponse([
                "status" => 500,
                "view" => $this->renderView("@ZghFE/Partial/products/user_profile_product_edit_widget.html.twig", [
                            "user" => $user,
                            "product" => $product,
                            "product_form" => $product_form->createView()
                        ]),
                "errors" => $product_form->getErrorsAsString()
            ]);
        }
        $this->getDoctrine()->getManager()->persist($product);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse(["status" => 200]);
    }

    /**
     * @Security("has_role('ROLE_VENDOR')")
     * @ParamConverter("product", class="ZghFEBundle:Product", options={"id" = "product_id"})
     * @param User $user
     * @param Product $product
     * @return JsonResponse
     */
    public function postDeleteAction(User $user, Product $product)
    {
        $deleter = $this->get("zgh_fe.delete_manager");
        $deleter->delete($product);
        return $this->redirect($this->generateUrl("zgh_fe.user_profile.products_partial", [
            "id" => $user->getId()
        ]));
    }

    /**
     * @ParamConverter("product", class="ZghFEBundle:Product", options={"id" = "product_id"})
     * @param User $user
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getProductContentAction(User $user, Product $product)
    {
        if($this->getUser() instanceof User) {
            $addWishlistForm = $this->createForm(new ProductWishlistType($this->get("security.context")), $product);
        }
        return $this->render("@ZghFE/Default/product_content.html.twig",[
                "is_popup" => false,
                "user" => $user,
                "product" => $product,
                "addWishlistForm" => $this->getUser() instanceof User ? $addWishlistForm->createView() : null
            ]);
    }

    /**
     * @ParamConverter("product", class="ZghFEBundle:Product", options={"id" = "product_id"})
     */
    public function getEditCurrentWishlistAction(User $user, Product $product)
    {
        $addWishlistForm = $this->createForm(new ProductWishlistType($this->get("security.context")), $product);
        return $this->render("@ZghFE/Partial/products/wishlist_edit_widget.html.twig", [
            "product" => $product,
            "form" => $addWishlistForm->createView(),
            "post_url" => $this->generateUrl("zgh_fe.products.add_to_wishlist", [
                    "id" => $user->getId(),
                    "product_id" => $product->getId()
                ])
        ]);
    }

    /**
     * @ParamConverter("product", class="ZghFEBundle:Product", options={"id" = "product_id"})
     */
    public function postAddToWishlistAction(Request $request, User $user, Product $product)
    {
        $addWishlistForm = $this->createForm(new ProductWishlistType($this->get("security.context")), $product);
        $addWishlistForm->handleRequest($request);

        if(!$addWishlistForm->isValid())
        {
            return new JsonResponse([
                "status" => 500,
                "errors" => $addWishlistForm->getErrorsAsString()
            ]);
        }
        $this->getDoctrine()->getManager()->persist($product);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse([
            "status" => 200
        ]);
    }

    /**
     * @ParamConverter("product", class="ZghFEBundle:Product", options={"id" = "product_id"})
     */
    public function getNewWishlistAction(User $user, Product $product)
    {
        $wishlist = new Wishlist();
        $new_form = $this->createForm(new WishlistType(), $wishlist);
        return $this->render("ZghFEBundle:Partial/wishlists:wishlist_add_widget.html.twig", [
            "form" => $new_form->createView(),
            "post_url" => $this->generateUrl("zgh_fe.products.post_wishlist_new", [
                    "id" => $user->getId(),
                    "product_id" => $product->getId(),
                ]),
            "product" => $product,
            "product_partial" => true
        ]);
    }

    /**
     * @ParamConverter("product", class="ZghFEBundle:Product", options={"id" = "product_id"})
     */
    public function postNewWishlistAction(Request $request, User $user, Product $product)
    {
        $current_user = $this->getUser();
        $wishlist = new Wishlist();
        $new_form = $this->createForm(new WishlistType(), $wishlist);
        $new_form->handleRequest($request);
        if(!$new_form->isValid())
        {
            return new JsonResponse([
                "status" => 500,
                "view" => $this->renderView("@ZghFE/Partial/wishlists/wishlist_add_widget.html.twig", [
                        "form" => $new_form->createView(),
                        "post_url" => $this->generateUrl("zgh_fe.products.post_wishlist_new", [
                                "id" => $user->getId(),
                                "product_id" => $product->getId(),
                            ])
                    ]),
                "errors" => $new_form->getErrorsAsString()
            ]);
        }
        $current_user->addWishlist($wishlist);
        $this->getDoctrine()->getManager()->persist($current_user);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse(['status' => 200]);
    }

    public function getSearchAction(Request $request, User $user)
    {
        $query = $request->query->get("q");
        $cat_slug = $request->query->get("category");
        $products = $this->get("zgh_fe.search_manager")->getProductByUserAndTag($user, $cat_slug, $query);
        return $this->render("@ZghFE/Partial/products/user_profile_products_content.html.twig",["user" => $user, "products" => $products]);
    }
}