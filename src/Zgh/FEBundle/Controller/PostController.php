<?php
namespace Zgh\FEBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Zgh\FEBundle\Entity\Comment;
use Zgh\FEBundle\Entity\Like;
use Zgh\FEBundle\Entity\Post;
use Zgh\FEBundle\Entity\PostImage;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Form\PostType;
use Zgh\FEBundle\Model\Event\NotifyDeleteEvent;

class PostController extends Controller
{
    public function getOwnListAction(Request $request, $id)
    {
        $offset = $request->query->get("f", null);
        $posts = $this->getDoctrine()->getRepository("ZghFEBundle:Post")->findPosts($id, $offset);

        //checking offset is not null to differentiate between tabbing ajax and load more ajax
        if($request->isXmlHttpRequest() && $offset != null)
        {
            return new JsonResponse([
               "view" => $this->renderView("@ZghFE/Partial/posts/posts_partials.html.twig", array(
                       "posts" => $posts
                   ))
            ]);
        } else {
            return $this->render("@ZghFE/Partial/posts/posts_partials.html.twig", array(
                "posts" => $posts
            ));
        }
    }

    public function getPublicListAction(Request $request)
    {
        $offset = $request->query->get("f", null);
        $user = $this->get("security.context")->getToken()->getUser();
        $posts = $this->getDoctrine()->getRepository("ZghFEBundle:User")->getPosts($user, $offset);
        if($request->isXmlHttpRequest() && $offset != null)
        {
            return new JsonResponse([
                "view" => $this->renderView("@ZghFE/Partial/posts/posts_partials.html.twig", array(
                        "posts" => $posts
                    ))
            ]);
        } else {
            return $this->render("@ZghFE/Partial/posts/posts_partials.html.twig", array(
                "posts" => $posts
            ));
        }
    }

    /**
     * @ParamConverter("post", class="ZghFEBundle:Post", options={"id" = "post_id"})
     *
     * @param User $user
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getDisplayAction(User $user, Post $post)
    {
        return $this->render("@ZghFE/Partial/posts/post_display.html.twig", array(
            "user" => $user,
            "post" => $post
        ));
    }

    public function postNewAction(Request $request)
    {
        $post = new Post();

        $form = $this->createForm(new PostType(), $post);
        $form->handleRequest($request);
        $return_url = $request->request->get("return_url");

        if(!$form->isValid())
        {
            $this->get("session")->getFlashBag()->set("val_error", $form->getErrors()->current()->getMessage());
            return $this->redirect($return_url);
        }

        $post->setUser($this->getUser());


        $this->getDoctrine()->getManager()->persist($post);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($return_url);
    }


    public function postDeleteAction(Request $request,Post $post)
    {
        $this->getDoctrine()->getManager()->remove($post);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl("zgh_fe.wall.index"));
    }
}