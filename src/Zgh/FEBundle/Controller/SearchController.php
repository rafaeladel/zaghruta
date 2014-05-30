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
        $search = new Search();
        $search_form = $this->createForm(new SearchType(), $search);
        $search_form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
//        $em->persist($search);
//        $em->flush();
        $products = $em->getRepository("ZghFEBundle:Product")->findBy(["name" => $search->getSearchText()]);
        return $this->render("@ZghFE/Partial/products/user_profile_products_content.html.twig", [
                "products" => $products
            ]);
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