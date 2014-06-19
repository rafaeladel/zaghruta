<?php
namespace Zgh\FEBundle\TwigExtension;

use Doctrine\ORM\EntityManagerInterface;
use Twig_Environment;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Model\CommentableInterface;
use Zgh\FEBundle\Service\RightSideManager;
use Zgh\FEBundle\Service\SearchManager;

class WidgetsExtension extends \Twig_Extension
{
    protected $em;

    protected $searchManager;

    protected $rightSideManager;

    /**
     * @var Twig_Environment
     */
    protected $env;

    public function __construct(EntityManagerInterface $entityManagerInterface, SearchManager $searchManager, RightSideManager $rightSideManager)
    {
        $this->em = $entityManagerInterface;
        $this->searchManager = $searchManager;
        $this->rightSideManager = $rightSideManager;
    }

    public function initRuntime(Twig_Environment $environment)
    {
        $this->env = $environment;
    }


    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction("getExperiences", [$this, "getExperiences"]),
            new \Twig_SimpleFunction("getProducts", [$this, "getProducts"]),
            new \Twig_SimpleFunction("getComments", [$this, "getComments"]),
            new \Twig_SimpleFunction("getSearchWidget", [$this, "getSearchWidget"] ),
            new \Twig_SimpleFunction("getProductsSearchResult", [$this, "getProductsSearchResult"] ),
            new \Twig_SimpleFunction("getPeopleSearchResult", [$this, "getPeopleSearchResult"] ),
            new \Twig_SimpleFunction("getVendorSearchResult", [$this, "getVendorSearchResult"] ),
            new \Twig_SimpleFunction("getTipsSearchResult", [$this, "getTipsSearchResult"] ),
            new \Twig_SimpleFunction("getExperiencesSearchResult", [$this, "getExperiencesSearchResult"] ),
            new \Twig_SimpleFunction("getProductsByCategory", [$this, "getProductsByCategory"] ),
            new \Twig_SimpleFunction("getVendorByCategory", [$this, "getVendorByCategory"] ),
            new \Twig_SimpleFunction("getExperienceByCategory", [$this, "getExperienceByCategory"] ),
            new \Twig_SimpleFunction("getRecommendedPeople", [$this, "getRecommendedPeople"] ),
            new \Twig_SimpleFunction("getRecommendedVendor", [$this, "getRecommendedVendor"] ),
            new \Twig_SimpleFunction("getNewVendors", [$this, "getNewVendors"] )

        ];
    }

    public function getExperiences(User $user)
    {
        $experiences = $this->em->getRepository("ZghFEBundle:Experience")->findByUser($user);
        return $experiences;
    }

    public function getComments(CommentableInterface $entity)
    {
        $comments = $entity->getComments();
        return $comments;
    }

    public function getProducts(User $user)
    {
        $products = $this->em->getRepository("ZghFEBundle:Product")->findByUser($user);
        return $products;
    }

    public function getSearchWidget()
    {
        $categories = $this->em->getRepository("ZghFEBundle:Category")->findAll();
        return $this->env->render("@ZghFE/Partial/search/search_header.html.twig", [
                "categories" => $categories
            ]);
    }

    public function getProductsSearchResult($crit)
    {
        $results = $this->searchManager->getProductsResults($crit);
        if(count($results) == 0)
        {
            return null;
        }
        return $this->env->render("@ZghFE/Partial/products/user_profile_products_content.html.twig", ["products" => $results]);
    }

    public function getPeopleSearchResult($crit)
    {
        $people = $this->searchManager->getPeopleResults($crit);
        if(count($people) == 0)
        {
            return null;
        }
        return $this->env->render("@ZghFE/Partial/search/search_partial_users.html.twig", ["users" => $people]);
    }

    public function getVendorSearchResult($crit)
    {
        $vendors = $this->searchManager->getVendorsResults($crit);
        if(count($vendors) == 0)
        {
            return null;
        }
        return $this->env->render("@ZghFE/Partial/search/search_partial_users.html.twig", ["users" => $vendors]);
    }

    public function getTipsSearchResult($crit)
    {
        $tips = $this->searchManager->getTipsResults($crit);
        if(count($tips) == 0)
        {
            return null;
        }
        return $this->env->render("@ZghFE/Partial/tips/user_profile_tip_content.html.twig", ["tips" => $tips]);
    }

    public function getExperiencesSearchResult($crit)
    {
        $experiences = $this->searchManager->getExperiencesResults($crit);
        if(count($experiences) == 0)
        {
            return null;
        }
        return $this->env->render("@ZghFE/Partial/experiences/user_profile_experience_content.html.twig", ["experiences" => $experiences]);
    }

    public function getProductsByCategory($cat_slug, $crit)
    {
        $results = $this->searchManager->getProductByCategoryResults($cat_slug, $crit);
        if(count($results) == 0)
        {
            return null;
        }
        return $this->env->render("@ZghFE/Partial/products/user_profile_products_content.html.twig", ["products" => $results]);
    }

    public function getVendorByCategory($cat_slug, $crit)
    {
        $vendor = $this->searchManager->getVendorByCategoryResults($cat_slug, $crit);
        if(count($vendor) == 0)
        {
            return null;
        }
        return $this->env->render("@ZghFE/Partial/search/search_partial_users.html.twig", ["users" => $vendor]);
    }

    public function getExperienceByCategory($cat_slug, $crit)
    {
        $experiences = $this->searchManager->getExperiencesByCategory($cat_slug, $crit);
        if(count($experiences) == 0)
        {
            return null;
        }
        return $this->env->render("@ZghFE/Partial/experiences/user_profile_experience_content.html.twig", ["experiences" => $experiences]);
    }

    public function getRecommendedPeople(User $user)
    {
        $result = $this->rightSideManager->getRecommendedPeople($user);
        $result_arr = [];
        foreach($result as $entry) {
            $result_arr[] = $entry[0];
        }
        return $this->env->render("@ZghFE/Partial/right_side/rightSidePartial.html.twig", [
                "result" => $result_arr
            ]);
    }

    public function getRecommendedVendor(User $user)
    {
        $result = $this->rightSideManager->getRecommendedVendors($user);
        $result_arr = [];
        foreach($result as $entry) {
            $result_arr[] = $entry[0];
        }
        return $this->env->render("@ZghFE/Partial/right_side/rightSidePartial.html.twig", [
                "result" => $result_arr
            ]);
    }

    public function getNewVendors(User $user)
    {
        $result = $this->rightSideManager->getNewVendors($user);
        return $this->env->render("@ZghFE/Partial/right_side/rightSidePartial.html.twig", [
                "result" => $result
            ]);
    }

    public function getName()
    {
        return "zgh_fe_widgets";
    }
}