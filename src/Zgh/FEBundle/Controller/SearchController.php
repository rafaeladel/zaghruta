<?php
namespace Zgh\FEBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
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

        return $this->render("@ZghFE/Default/search.html.twig", ["query" => $query, "catId" => $cat_id]);
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