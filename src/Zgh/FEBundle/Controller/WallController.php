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
}