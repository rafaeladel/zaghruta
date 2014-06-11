<?php
namespace Zgh\FEBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Entity\Wishlist;
use Zgh\FEBundle\Form\WishlistType;

class WishlistController extends Controller
{
    public function getWishlistPartialContentAction($id)
    {
        $user = $this->getDoctrine()->getRepository("ZghFEBundle:User")->find($id);
        $wishlists = $user->getWishlists();
        return $this->render("@ZghFE/Partial/wishlists/user_profile_wishlist_content.html.twig", array(
                "user" => $user,
                "wishlists" => $wishlists
            ));
    }

    /**
     * @ParamConverter("wishlist", class="ZghFEBundle:Wishlist", options={"id" = "wishlist_id"})
     */
    public function getWishlistIndexAction(User $user, Wishlist $wishlist)
    {
        return $this->render("@ZghFE/Partial/wishlists/user_profile_wishlist_index.html.twig", array(
                "user" => $user,
                "wishlist" => $wishlist
            ));
    }


    /**
     * @ParamConverter("wishlist", class="ZghFEBundle:Wishlist", options={"id" = "wishlist_id"})
     */
    public function deleteAction(User $user, Wishlist $wishlist)
    {
        $this->get("zgh_fe.delete_manager")->delete($wishlist);
        return $this->redirect($this->generateUrl("zgh_fe.user_profile.wishlist_partial",[
            "id" => $user->getId()
        ]));
    }



    /**
     * @Security("has_role('ROLE_CUSTOMER')")
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function postNewAction(Request $request, $id)
    {
//        $user = $this->get("security.context")->getToken()->getUser();
        $user = $this->getDoctrine()->getRepository("ZghFEBundle:User")->find($id);
        $wishlist = new Wishlist();
        $form = $this->createForm(new WishlistType(), $wishlist);
        $form->handleRequest($request);
        $user->addWishlist($wishlist);
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse(array("status" => 200));
    }

    public function getSerializedAction()
    {
        $wishlists_arr = [];
        $wishlists = $this->getUser()->getWishlists();
        foreach ($wishlists as $wishlist) {
            $wishlist_entry = [];
            $wishlist_entry['id'] = $wishlist->getName();
            $wishlist_entry['text'] = $wishlist->getName();
            $wishlists_arr[] = $wishlist_entry;
        }
        return new JsonResponse($wishlists_arr);

    }
}