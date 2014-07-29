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
class Version20140726171030 extends AbstractMigration implements ContainerAwareInterface
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
                "name" => "Cakes & Catering",
                "css_class" => "icon-cakeCatering",
                "sub" => [
                    [
                        "name" => "Cakes & Desserts",
                        "css_class" => "icon-cakeCatering",
                    ],                    [
                        "name" => "Food & Catering",
                        "css_class" => "icon-cakeCatering",
                    ],
                ]
            ],
            [
                "name" => "Photography",
                "css_class" => "icon-photography",
                "sub" => [
                    [
                        "name" => "Photography",
                        "css_class" => "icon-photography"
                    ],
                    [
                        "name" => "Videography",
                        "css_class" => "icon-photography"
                    ]
                ]
            ],
            [
                "name" => "Beauty & Health",
                "css_class" => "icon-Health",
                "sub" => [
                    [
                        "name" => "Makeup",
                        "css_class" => "icon-Health",
                    ],                    [
                        "name" => "Hair Stylists",
                        "css_class" => "icon-Health",
                    ],
                    [
                        "name" => "Beauty Centers",
                        "css_class" => "icon-Health",
                    ],
                ]
            ],
            [
                "name" => "Fashion",
                "css_class" => "icon-fashion",
                "sub" => [
                    [
                        "name" => "Bridal Wear",
                        "css_class" => "icon-fashion",
                    ],                    [
                        "name" => "Groom Wear",
                        "css_class" => "icon-fashion",
                    ]
                ]
            ],
            [
                "name" => "Furniture",
                "css_class" => "icon-furniture",
                "sub" => [
                    [
                        "name" => "Interior Design",
                        "css_class" => "icon-furniture",
                    ],
                    [
                        "name" => "Flooring",
                        "css_class" => "icon-furniture",
                    ],
                    [
                        "name" => "Furniture",
                        "css_class" => "icon-furniture",
                    ]
                ]
            ]
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
