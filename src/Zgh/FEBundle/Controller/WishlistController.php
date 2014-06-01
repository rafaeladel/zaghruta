<?php
namespace Zgh\FEBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Zgh\FEBundle\Entity\Wishlist;
use Zgh\FEBundle\Form\WishlistType;

class WishlistController extends Controller
{
    public function getWishlistPartialContentAction($id)
    {
        $user = $this->getDoctrine()->getRepository("ZghFEBundle:User")->find($id);
        $wishlists = $user->getWishlists();
        return $this->render("@ZghFE/Partial/wishlists/user_profile_wishlist_content.html.twig", array(
                "wishlists" => $wishlists
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