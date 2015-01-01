<?php
namespace Zgh\FEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TagController extends Controller
{
    public function getSerializedAction(Request $request, $id)
    {
        $tags_arr = [];
        $criteria = $request->query->get("q");
        $tags = $this->getDoctrine()->getRepository("ZghFEBundle:Tag")->findSearchResult($criteria, $id);
        foreach ($tags as $tag) {
            $tag_entry = [];
            $tag_entry['id'] = $tag->getName();
            $tag_entry['text'] = $tag->getName();
            $tags_arr[] = $tag_entry;
        }
        return new JsonResponse($tags_arr);

    }
}