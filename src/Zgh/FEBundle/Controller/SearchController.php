<?php
namespace Zgh\FEBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Zgh\FEBundle\Entity\Search;
use Zgh\FEBundle\Form\SearchType;

class SearchController extends Controller
{
    public function startSearchAction(Request $request)
    {
        $query = $request->query->get("q");
        $cat_slug = $request->query->get("category");
        $category_obj = $cat_slug != "all" && $cat_slug != "people"
                            ? $this->getDoctrine()->getRepository("ZghFEBundle:Category")->findOneBy(["name_slug" => $cat_slug])
                            : $cat_slug;
        if($category_obj == null)
        {
            throw new NotFoundHttpException("Not a valid category");
        }
        return $this->render(
            "@ZghFE/Default/search.html.twig",
            ["query" => $query, "catSlug" => $cat_slug, "cat_obj" => $category_obj]
        );
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