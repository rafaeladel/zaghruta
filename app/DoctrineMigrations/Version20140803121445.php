<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Zgh\FEBundle\Entity\Category;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140803121445 extends AbstractMigration implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function up(Schema $schema)
    {
        $em = $this->container->get("doctrine.orm.entity_manager");
        $categories_meta = [
            [
                "name" => "Hotels & Venues",
                "css_class" => "icon-hotelsVenues",
                "sub" => []
            ],
            [
                "name" => "Limousines",
                "css_class" => "icon-limousines",
                "sub" => []
            ],
            [
                "name" => "Home Appliances",
                "css_class" => "icon-homeAppliances",
                "sub" => []
            ],
            [
                "name" => "Entertainment",
                "css_class" => "icon-entertainment",
                "sub" => []
            ],
            [
                "name" => "Wedding Planners",
                "css_class" => "icon-weddingPlanners",
                "sub" => []
            ],
            [
                "name" => "Jewlery",
                "css_class" => "icon-jewlery",
                "sub" => []
            ],
            [
                "name" => "Honeymoons",
                "css_class" => "icon-honeymoons",
                "sub" => []
            ],
            [
                "name" => "Real Estate",
                "css_class" => "icon-realEstate",
                "sub" => []
            ],
            [
                "name" => "Florists",
                "css_class" => "icon-florists",
                "sub" => []
            ],

        ];

        foreach($categories_meta as $main_category)
        {
            $category = new Category();
            $category->setName($main_category["name"]);
            $category->setCssClass($main_category["css_class"]);

            foreach($main_category["sub"] as $sub_category_meta)
            {
                if($main_category["name"] == $sub_category_meta["name"])
                {
                    $category->addSubCategory($category);
                    continue;
                }
                $sub_category = new Category();
                $sub_category->setName($sub_category_meta["name"]);
                $sub_category->setCssClass($sub_category_meta["css_class"]);
                $category->addSubCategory($sub_category);
            }
            $em->persist($category);
        }
        $em->flush();
    }

    public function down(Schema $schema)
    {
        $em = $this->container->get("doctrine.orm.entity_manager");
        $categories = $em->getRepository("ZghFEBundle:Category")->findAll();
        if($categories){
            foreach($categories as $category) {
                $category->setParentCategory(null);
                $em->persist($category);
            }
            $em->flush();

            foreach($categories as $category) {
                $em->remove($category);
            }
            $em->flush();

        }
    }
}
