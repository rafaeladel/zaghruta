<?php
namespace Zgh\FEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Zgh\FEBundle\Entity\Search;
use Zgh\FEBundle\Form\SearchType;

class SearchController extends Controller
{
    public function postSearchAction(Request $request)
    {
        $query = $request->query->get("q");
        $cat_id = $request->query->get("category");

        if($cat_id == -1) {
            $results = $this->get("zgh_fe.search_manager")->getAllResults($query);
        } else if ($cat_id == -2) {
            $results = $this->get("zgh_fe.search_manager")->getPeopleResults($query);
        } else {
            $results = $this->get("zgh_fe.search_manager")->getCategoryResults($cat_id, $query);
        }

        return $this->render("@ZghFE/Default/search.html.twig", ["results" => $results]);
    }

    public function getSearchJsonAction($term)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository("ZghFEBundle:Product")->findBy(["name" => $term]);
        $names = [];
        foreach ($products as $product) {
            $names[] = $product->getName();
        }
        return new JsonResponse($names);
    }

}