<?php
namespace Zgh\FEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class TagController extends Controller
{
    public function getSerializedAction()
    {
        $tags_arr = [];
        $tags = $this->getDoctrine()->getRepository("ZghFEBundle:Tag")->findAll();
        foreach ($tags as $tag) {
            $tag_entry = [];
            $tag_entry['id'] = $tag->getName();
            $tag_entry['text'] = $tag->getName();
            $tags_arr[] = $tag_entry;
        }
        return new JsonResponse($tags_arr);

    }
}