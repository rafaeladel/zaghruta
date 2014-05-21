<?php
namespace Zgh\FEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Zgh\FEBundle\Entity\Comment;

class CommentController extends Controller
{
    public function postCommentAction(Request $request, $id, $entity_type)
    {
        $entity = null;
        $em = $this->getDoctrine()->getManager();
        if($entity_type == 0){
            $entity = $em->getRepository(Comment::getTypes()[Comment::COMMENT_TYPE_POST])->find($id);
        } else if($entity_type == 1){
            $entity = $em->getRepository(Comment::getTypes()[Comment::COMMENT_TYPE_PHOTO])->find($id);
        } else if($entity_type == 2){
            $entity = $em->getRepository(Comment::getTypes()[Comment::COMMENT_TYPE_EXPERIENCE])->find($id);
        } else if($entity_type == 3){
            $entity = $em->getRepository(Comment::getTypes()[Comment::COMMENT_TYPE_TIP])->find($id);
        }

        return $this->get("zgh_fe.comment_manager")->postComment($request, $entity);
    }

    public function deleteCommentAction($id)
    {
        $comment = $this->getDoctrine()->getRepository("ZghFEBundle:Comment")->find($id);
        return $this->get("zgh_fe.comment_manager")->deleteComment($comment);
    }
}