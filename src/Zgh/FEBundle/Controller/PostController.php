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
    public function getOwnListAction($id)
    {
        $posts = $this->getDoctrine()->getRepository("ZghFEBundle:Post")->findPosts($id);
        return $this->render("@ZghFE/Partial/posts/posts_partials.html.twig", array(
                "posts" => $posts
            ));
    }

    public function getPublicListAction()
    {
        $user = $this->get("security.context")->getToken()->getUser();
        $posts = $this->getDoctrine()->getRepository("ZghFEBundle:User")->getPosts($user);
        return $this->render("@ZghFE/Partial/posts/posts_partials.html.twig", array(
                "posts" => $posts
            ));
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
        $photo = $request->files->get("post")["post_image"];
        $content = $request->request->get("post")["content"];
        $return_url = $request->request->get("return_url");
        $post = new Post();

        if($content != null)
        {
            $youtube_arr = array();
            $vimeo_arr = array();
            $embedded_arr = array();

            if(preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',$content, $youtube_arr))
            {
                $format = "<iframe width=\"482\" height=\"315\" src=\"//www.youtube.com/embed/%s\" frameborder=\"0\" allowfullscreen></iframe>";
                $embedded_arr["youtube"] = sprintf($format, $youtube_arr[1]);
            }

            if(preg_match('/(?:https?:)?(?:\/\/)?(?:www\.)?vimeo.com\/(\d+)($|\/)/', $content, $vimeo_arr))
            {
                $format = "<iframe src=\"//player.vimeo.com/video/%s\" width=\"482\" height=\"315\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
                $embedded_arr["vimeo"] = sprintf($format, $vimeo_arr[1]);
            }


            $post->setVideo(array_shift($embedded_arr));

        }

        $post->setContent($content);

        if($photo != null)
        {
            $post->setImage((new PostImage())->setImageFile($photo));
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