<?php
namespace Zgh\FEBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Zgh\FEBundle\Entity\Category;

class LoadBasicData implements FixtureInterface {

	/**
	 * Load data fixtures with the passed EntityManager
	 * @param ObjectManager $manager
	 */
	function load(ObjectManager $manager) {
		$categories_meta = [
			[
				"name" => "Cakes & Catering",
				"css_class" => "icon-cakeCatering",
				"sub" => [
					[
						"name" => "Cakes & Desserts",
						"css_class" => "icon-cakeCatering",
					],
					[
						"name" => "Food & Catering",
						"css_class" => "icon-cakeCatering",
					],
				],
				"is_hidden" => false
			],
			[
				"name" => "Photography",
				"css_class" => "icon-photography",
				"sub" => [],
				"is_hidden" => false
			],
			[
				"name" => "Beauty & Health",
				"css_class" => "icon-Health",
				"sub" => [
					[
						"name" => "Makeup",
						"css_class" => "icon-Health",
					], [
						"name" => "Hair Stylists",
						"css_class" => "icon-Health",
					],
					[
						"name" => "Beauty Centers",
						"css_class" => "icon-Health",
					],
				],
				"is_hidden" => false
			],
			[
				"name" => "Fashion",
				"css_class" => "icon-fashion",
				"sub" => [
					[
						"name" => "Bridal Wear",
						"css_class" => "icon-fashion",
					],
					[
						"name" => "Groom Wear",
						"css_class" => "icon-fashion",
					]
				],
				"is_hidden" => false
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
						"name" => "Indoor Furniture",
						"css_class" => "icon-furniture",
					],
					[
						"name" => "Outdoor Furniture",
						"css_class" => "icon-furniture",
					]
				],
				"is_hidden" => false
			],
			[
				"name" => "Hotels & Venues",
				"css_class" => "icon-hotelsVenues",
				"sub" => [],
				"is_hidden" => false
			],
			[
				"name" => "Limousines",
				"css_class" => "icon-limousines",
				"sub" => [],
				"is_hidden" => true
			],
			[
				"name" => "Home Appliances",
				"css_class" => "icon-homeAppliances",
				"sub" => [],
				"is_hidden" => true
			],
			[
				"name" => "Entertainment",
				"css_class" => "icon-entertainment",
				"sub" => [],
				"is_hidden" => false
			],
			[
				"name" => "Wedding Planners",
				"css_class" => "icon-weddingPlanners",
				"sub" => [],
				"is_hidden" => false
			],
			[
				"name" => "Jewelry",
				"css_class" => "icon-jewlery",
				"sub" => [],
				"is_hidden" => false
			],
			[
				"name" => "Honeymoons",
				"css_class" => "icon-honeymoons",
				"sub" => [],
				"is_hidden" => false
			],
			[
				"name" => "Real Estate",
				"css_class" => "icon-realEstate",
				"sub" => [],
				"is_hidden" => true
			],
			[
				"name" => "Florists",
				"css_class" => "icon-florists",
				"sub" => [],
				"is_hidden" => false
			],

		];

		foreach ($categories_meta as $main_category) {
			$category = new Category();
			$category->setName($main_category["name"]);
			$category->setCssClass($main_category["css_class"]);
			$category->setIsHidden($main_category["is_hidden"]);

			foreach ($main_category["sub"] as $sub_category_meta) {
				if ($main_category["name"] == $sub_category_meta["name"]) {
					$category->addSubCategory($category);
					continue;
				}
				$sub_category = new Category();
				$sub_category->setName($sub_category_meta["name"]);
				$sub_category->setCssClass($sub_category_meta["css_class"]);
				$sub_category->setIsHidden($main_category["is_hidden"]);
				$category->addSubCategory($sub_category);
			}
			$manager->persist($category);
		}
		$manager->flush();
	}
}