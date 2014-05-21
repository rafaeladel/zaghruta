<?php
namespace Zgh\FEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Zgh\FEBundle\Entity\Like;

class LikeController extends Controller
{
    public function getLikesAction($id, $entity_type)
    {
        $entity = null;
        $em = $this->getDoctrine()->getManager();
        if($entity_type == 0){
            $entity = $em->getRepository(Like::getTypes()[Like::LIKE_TYPE_POST])->find($id);
        } else if($entity_type == 1){
            $entity = $em->getRepository(Like::getTypes()[Like::LIKE_TYPE_PHOTO])->find($id);
        } else if($entity_type == 2){
            $entity = $em->getRepository(Like::getTypes()[Like::LIKE_TYPE_EXPERIENCE])->find($id);
        } else if($entity_type == 3){
            $entity = $em->getRepository(Like::getTypes()[Like::LIKE_TYPE_TIP])->find($id);
        }

        $likes = $entity->getLikes();
        return $this->render("@ZghFE/Partial/likes_popup.html.twig", array("likes" => $likes));
    }


    public function postLikeAction($id, $entity_type)
    {
        $entity = null;
        $em = $this->getDoctrine()->getManager();

        if($entity_type == 0){
            $entity = $em->getRepository(Like::getTypes()[Like::LIKE_TYPE_POST])->find($id);
        } else if($entity_type == 1){
            $entity = $em->getRepository(Like::getTypes()[Like::LIKE_TYPE_PHOTO])->find($id);
        } else if($entity_type == 2){
            $entity = $em->getRepository(Like::getTypes()[Like::LIKE_TYPE_EXPERIENCE])->find($id);
        } else if($entity_type == 3){
            $entity = $em->getRepository(Like::getTypes()[Like::LIKE_TYPE_TIP])->find($id);
        }

        return $this->get("zgh_fe.like_manager")->postLike($entity);
    }
}