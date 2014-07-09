<?php
namespace Zgh\FEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Zgh\FEBundle\Entity\Branch;
use Zgh\FEBundle\Entity\Experience;
use Zgh\FEBundle\Entity\Post;
use Zgh\FEBundle\Entity\Product;
use Zgh\FEBundle\Entity\Search;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Entity\UserInfo;
use Zgh\FEBundle\Entity\UserPP;
use Zgh\FEBundle\Entity\Wishlist;
use Zgh\FEBundle\Form\BranchType;
use Zgh\FEBundle\Form\ExperienceType;
use Zgh\FEBundle\Form\PostType;
use Zgh\FEBundle\Form\ProductType;
use Zgh\FEBundle\Form\SearchType;
use Zgh\FEBundle\Form\UserInfoType;
use Zgh\FEBundle\Form\VendorInfoType;
use Zgh\FEBundle\Form\VendorIntroType;
use Zgh\FEBundle\Form\WishlistType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Zgh\FEBundle\Form\UserIntroType\IntroUserType;

class UserProfileController extends Controller
{
    public function indexAction($id)
    {
        $user = $this->getDoctrine()->getRepository("ZghFEBundle:User")->find($id);
        $post_form = $this->createForm(new PostType(), new Post());

        if ($user == null) {
            return $this->render("@ZghFE/Default/404.html.twig");
        } elseif (!in_array("ROLE_FACEBOOK", $user->getRoles())) {
            if ($user->getFirstTime() == true) {
                return $this->forward("ZghFEBundle:UserProfile:getUserIntro", array("id" => $user->getId()));
            }
        }

        return $this->render('ZghFEBundle:Default:user_index.html.twig', array(
            "user" => $user,
            'post_form' => $post_form->createView()
        ));
    }

    public function getUserIntroAction($id)
    {
        $user = $this->getDoctrine()->getRepository("ZghFEBundle:User")->find($id);
        if (in_array("ROLE_CUSTOMER", $user->getRoles())) {
            return $this->getCustomerInfo();
        } elseif (in_array("ROLE_VENDOR", $user->getRoles())) {
            return $this->getVendorInfo();
        }
        return false;
    }

    private function getCustomerInfo()
    {
        $form = $this->createForm(new IntroUserType(), null);
        return $this->render("ZghFEBundle:Default:customer_intro.html.twig", array(
            "form" => $form->createView()
        ));
    }

    private function getVendorInfo()
    {
        return $this->redirect($this->generateUrl("zgh_fe.vendor_categories.get"));
//        /**
//         * @var User
//         */
//        $user = $this->getUser();
//
//        $defaultData = array("message" => "Default form data");
//        $form = $this->createForm(new VendorIntroType(), $user->getVendorInfo());
////        $form = $this->createFormBuilder($defaultData)
////            ->add("company_name", "text", array("mapped" => false));
//        return $this->render("@ZghFE/Default/vendor_intro.html.twig", array(
//            "form" => $form->createView()
//        ));
    }

    public function postUserIntroAction(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository("ZghFEBundle:User")->find($id);
        if (in_array("ROLE_CUSTOMER", $user->getRoles())) {
            return $this->postCustomerInfo($request, $user);
        } elseif (in_array("ROLE_VENDOR", $user->getRoles())) {
            return $this->postVendorInfo($request, $user);
        }
        return false;
    }

    private function postCustomerInfo($request,User $user)
    {
        $form = $this->createForm(new IntroUserType());
        $form->handleRequest($request);

        if($form->isValid())
        {
            $first_name = $form->get("name")->get("firstname")->getData();
            $last_name = $form->get("name")->get("lastname")->getData();
            $user->setFirstname($first_name);
            $user->setLastname($last_name);

            $user_info = $user->getUserInfo();
            $birthday = $form->get("info")->get("birthday")->getData();
            $gender = $form->get("info")->get("gender")->getData();
            $user_info->setBirthday($birthday);
            $user_info->setGender($gender);

            $user->setFirstTime(false);

            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->persist($user_info);
            $em->flush();
            return $this->redirect($this->generateUrl("zgh_fe.wall.index"));
        }

        return $this->render("@ZghFE/Default/customer_intro.html.twig", [
           "form" => $form->createView()
        ]);

    }

    private function postVendorInfo($request, $user)
    {
        $em = $this->getDoctrine()->getManager();
        $vendor_info= $user->getVendorInfo();
        $form = $this->createForm(new VendorIntroType(), $vendor_info);
        $form->handleRequest($request);
        if(!$form->isValid()) {
            return $this->render("@ZghFE/Default/vendor_intro.html.twig", [
                    "form" => $form->createView()
                ]);
        }

        $user->setFirstname($vendor_info->getCompanyName());
        $user->setFirstTime(false);
        $em->persist($vendor_info);
        $em->persist($user);
        $em->flush();
        return $this->redirect($this->generateUrl("zgh_fe.wall.index"));
    }

    public function getMainPartialAction($id)
    {
        $user = $this->getDoctrine()->getRepository("ZghFEBundle:User")->find($id);
        $authorized = $this->get("zgh_fe.user_privacy.manager")->isVisitable($user);
        if (!$authorized) {
            return $this->redirect($this->generateUrl("zgh_fe.user_profile.index", array("id" => $id)));
        }

        $post_form = $this->createForm(new PostType(), new Post());
        return $this->render('@ZghFE/Partial/common/user_profile_main.html.twig', array(
            'user' => $user,
            'post_form' => $post_form->createView()
        ));
    }

    public function getAboutPartialAction($id)
    {
        $user = $this->getDoctrine()->getRepository("ZghFEBundle:User")->find($id);
        if (in_array("ROLE_CUSTOMER", $user->getRoles())) {
            $about = $user->getUserInfo();
            return $this->render('@ZghFE/Partial/about/user_profile_about_customer.html.twig', array(
                "about" => $about
            ));
        } else if (in_array("ROLE_VENDOR", $user->getRoles())) {
            $about = $user->getVendorInfo();
            return $this->render('@ZghFE/Partial/about/user_profile_about_vendor.html.twig', array(
                "about" => $about
            ));
        }
    }

    public function getBranchPartialAction($id)
    {
        $user = $this->getDoctrine()->getRepository("ZghFEBundle:User")->find($id);
        $form = $this->createForm(new BranchType(), new Branch());
        return $this->render("@ZghFE/Partial/branches/user_profile_branches.html.twig", [
            "user" => $user,
            "form" => $form->createView()
        ]);
    }

    public function getWishlistPartialAction($id)
    {
        $user = $this->getDoctrine()->getRepository("ZghFEBundle:User")->find($id);

        $authorized = $this->get("zgh_fe.user_privacy.manager")->isVisitable($user);
        if (!$authorized) {
            return $this->redirect($this->generateUrl("zgh_fe.user_profile.index", array("id" => $id)));
        }

        $form = $this->createForm(new WishlistType(), new Wishlist());
        return $this->render("@ZghFE/Partial/wishlists/user_profile_wishlist.html.twig", array(
            "user" => $user,
            "wishlist_form" => $form->createView()
        ));
    }

    public function getProductsPartialAction(Request $request, User $user)
    {

        $authorized = $this->get("zgh_fe.user_privacy.manager")->isVisitable($user);
        if (!$authorized) {
            return $this->redirect($this->generateUrl("zgh_fe.user_profile.index", array("id" => $user->getId())));
        }

        $query = $request->query->get("q", null);
        $cat_id = $request->query->get("category");
        if ($query != null) {
            $products = $this->get("zgh_fe.search_manager")->getProductByUserAndCategory($user, $cat_id, $query);
        } else {
            $products = $user->getProducts();
        }

        $this->get("zgh_fe.paginator")->getNext($products, $products->first());


        return $this->render("@ZghFE/Partial/products/user_profile_products.html.twig", array(
            "user" => $user,
            "products" => $products
        ));
    }

    public function getPhotosPartialAction($id)
    {
        $user = $this->getDoctrine()->getRepository("ZghFEBundle:User")->find($id);

        $authorized = $this->get("zgh_fe.user_privacy.manager")->isVisitable($user);
        if (!$authorized) {
            return $this->redirect($this->generateUrl("zgh_fe.user_profile.index", array("id" => $id)));
        }
        $albums = $user->getAlbums();
        return $this->render("@ZghFE/Partial/photos/user_profile_photos.html.twig", array(
            "user" => $user,
            "albums" => $albums
        ));
    }

    public function getAlbumsPartialAction($id)
    {
        $user = $this->getDoctrine()->getRepository("ZghFEBundle:User")->find($id);

        $authorized = $this->get("zgh_fe.user_privacy.manager")->isVisitable($user);
        if (!$authorized) {
            return $this->redirect($this->generateUrl("zgh_fe.user_profile.index", array("id" => $id)));
        }
        $albums = $user->getAlbums();
        return $this->render("@ZghFE/Partial/photos/user_profile_albums.html.twig", array(
            "user" => $user,
            "albums" => $albums
        ));
    }

    public function getExperiencesPartialsAction($id)
    {
        $user = $this->getDoctrine()->getRepository("ZghFEBundle:User")->find($id);

        $authorized = $this->get("zgh_fe.user_privacy.manager")->isVisitable($user);
        if (!$authorized) {
            return $this->redirect($this->generateUrl("zgh_fe.user_profile.index", array("id" => $id)));
        }
        return $this->render("@ZghFE/Partial/experiences/user_profile_experiences.html.twig", array(
            "user" => $user,
        ));
    }

    public function getTipsPartialsAction($id)
    {
        $user = $this->getDoctrine()->getRepository("ZghFEBundle:User")->find($id);

//        $authorized = $this->get("zgh_fe.user_privacy.manager")->isVisitable($user);
//        if(!$authorized)
//        {
//            return $this->redirect($this->generateUrl("zgh_fe.user_profile.index", array("id" => $id)));
//        }

        return $this->render("@ZghFE/Partial/tips/user_profile_tips.html.twig", array(
            "user" => $user,
        ));
    }

    public function getConnectionsPartialsAction(User $user)
    {
        return $this->render("@ZghFE/Partial/connections/user_profile_connections.html.twig", array(
            "user" => $user
        ));
    }

    public function postProfilePictureAction(Request $request, $id)
    {
        $user = $this->get("security.context")->getToken()->getUser();
        $pic_file = $request->files->get("picture");
        $pic = $user->getProfilePhoto();
        $pic->setImageFile($pic_file);

        $this->getDoctrine()->getManager()->persist($pic);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($this->generateUrl("zgh_fe.user_profile.index", array("id" => $user->getId())));
    }

    public function postCoverPictureAction(Request $request, $id)
    {
        $user = $this->get("security.context")->getToken()->getUser();
        $pic_file = $request->files->get("cover");

        $temp_height = $request->query->get("temp_height");
        $temp_width = $request->query->get("temp_width");
        $height = $request->query->get("height");
        $width = $request->query->get("width");
        $x = $request->query->get("x");
        $y = $request->query->get("y");

        $pic = $user->getCoverPhoto();

        $pic->setTempImgSize(array('temp_height' => $temp_height, 'temp_width' => $temp_width));
        $pic->setCropCoordinates(array('height' => $height, 'width' => $width, 'x' => $x, 'y' => $y));
        $pic->setImageFile($pic_file);

        $this->getDoctrine()->getManager()->persist($pic);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($this->generateUrl("zgh_fe.user_profile.index", array("id" => $user->getId())));
    }

    public function postSetPrivacyAction($id, $pv)
    {
        $user = $this->get("security.context")->getToken()->getUser();
        $user->setIsPrivate($pv);
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse(array("status" => 200));
    }


    public function setEmailNotificationAction(User $user)
    {
        $user->setEmailNotification(!$user->getEmailNotification());
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse(array("status" => 200));
    }

}