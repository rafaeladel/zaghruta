<?php
namespace Zgh\FEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Zgh\FEBundle\Entity\Comment;
use Zgh\FEBundle\Entity\Post;
use Zgh\FEBundle\Form\CommentType;
use Zgh\FEBundle\Form\PostType;

class WallController extends Controller
{
    public function indexAction()
    {
        $post_form = $this->createForm(new PostType(), new Post());
        $user = $this->get("security.context")->getToken()->getUser();
        if(!in_array("ROLE_FACEBOOK", $user->getRoles()))
        {
            if ($user->getFirstTime() == true)
            {
                return $this->forward("ZghFEBundle:UserProfile:getUserIntro", array("id" => $user->getId()));
            }
        }

        return $this->render("@ZghFE/Default/wall.html.twig", array(
                "user" => $user,
                "post_form" => $post_form->createView()
            ));
    }

    public function getRecommendedPeopleAction()
    {
        $result = $this->get("zgh_fe.right_side_manager")->getRecommendedPeople($this->getUser());
        $result_arr = [];
        foreach($result as $entry) {
            $result_arr[] = $entry[0];
        }
        return $this->render("@ZghFE/Partial/right_side/list_friends.html.twig", [
                "title" => "Recommended people",
                "users" => $result_arr
            ]);
    }

    public function getRecommendedVendorsAction()
    {
        $result = $this->get("zgh_fe.right_side_manager")->getRecommendedVendors($this->getUser());
        $result_arr = [];
        foreach($result as $entry) {
            $result_arr[] = $entry[0];
        }
        return $this->render("@ZghFE/Partial/right_side/list_friends.html.twig", [
                "title" => "Recommended vendors",
                "users" => $result_arr
            ]);
    }

    public function getNewVendorsAction()
    {
        $users = $this->get("zgh_fe.right_side_manager")->getNewVendors($this->getUser());
        return $this->render("@ZghFE/Partial/right_side/list_friends.html.twig", [
                "title" => "New vendors",
                "users" => $users
            ]);
    }
}