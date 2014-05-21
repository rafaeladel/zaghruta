<?php
namespace Zgh\FEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Zgh\FEBundle\Entity\Comment;
use Zgh\FEBundle\Entity\Like;
use Zgh\FEBundle\Entity\Post;
use Zgh\FEBundle\Entity\PostImage;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Form\PostType;

class PostController extends Controller
{
    public function getOwnListAction($id)
    {
        $posts = $this->getDoctrine()->getRepository("ZghFEBundle:Post")->findPosts($id);
        return $this->render("@ZghFE/Partial/posts_partials.html.twig", array(
                "posts" => $posts
            ));
    }

    public function getPublicListAction()
    {
        $user = $this->get("security.context")->getToken()->getUser();
        $posts = $this->getDoctrine()->getRepository("ZghFEBundle:User")->getPosts($user);
        return $this->render("@ZghFE/Partial/posts_partials.html.twig", array(
                "posts" => $posts
            ));
    }

    public function postNewAction(Request $request)
    {
        $photo = $request->files->get("post")["post_image"];
        $content = $request->request->get("post")["content"];
        $return_url = $request->request->get("post")["return_url"];
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


        $token = $this->get("security.context")->getToken();

        $post->setContent($content);

        if($photo != null)
        {
            $post->setImage((new PostImage())->setImageFile($photo));
        }

        $post->setUser($token->getUser());


        $this->getDoctrine()->getManager()->persist($post);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($return_url);
    }


    public function postLikeAction(Request $request, Post $post)
    {
        return $this->get("zgh_fe.like_manager")->postLike($post);
    }

    public function postCommentAction(Request $request, Post $post)
    {
        $content = $request->request->get("comment_content", null);
//        var_dump($content);
//        die;
        if($content == null)
        {
            return $this->redirect($this->generateUrl("zgh_fe.wall.index"));
        }

        $user = $this->get("security.context")->getToken()->getUser();
        $comment = new Comment();
        $comment->setUser($user);
        $comment->setContent($content);
        $post->addComment($comment);
        $this->getDoctrine()->getManager()->persist($post);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse(array(
            "deleteUrl" => $this->generateUrl("zgh_fe.post.comment.delete", array("id" => $comment->getId())),
            "author" => $comment->getUser()->getFullName(),
            "author_url" => "user_url",
            "time" => $comment->getCreatedAt()->format("D - h A"),
            "comments_count" => count($post->getComments()),
            "author_pp" => in_array("ROLE_FACEBOOK", $user->getRoles()) ?
                                        'https://graph.facebook.com/'.$user->getFacebookId().'/picture' :
                                        str_replace("app_dev.php", "", $request->getUriForPath($user->getProfilePhoto()->getWebPath()))
        ));
    }

    public function postCommentDeleteAction(Request $request, $id)
    {
        $comment = $this->getDoctrine()->getRepository("ZghFEBundle:Comment")->find($id);
        $this->getDoctrine()->getManager()->remove($comment);
        $this->getDoctrine()->getManager()->flush();
        $post = $comment->getPost();
        $count = count($post->getComments());
//        return $this->redirect($this->generateUrl("zgh_fe.wall.index"));
        return new JsonResponse(array("comments_count" => $count));
    }

    public function postDeleteAction(Request $request,Post $post)
    {
        $this->getDoctrine()->getManager()->remove($post);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($this->generateUrl("zgh_fe.wall.index"));
    }
}