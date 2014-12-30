<?php
namespace Zgh\FEBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Zgh\FEBundle\Transformer\MultiCategoryTransformer;

class TipType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $cat_transformer = new MultiCategoryTransformer();

        $builder
            ->add("title", "text", [
                "attr" => [
                    "maxlength" => 65
                ]
            ])
            ->add("content", "textarea");

        $builder->add(
            $builder->create("categories", "thrace_select2_entity", array(
                "class" => 'Zgh\FEBundle\Entity\Category',
                "query_builder" => function(EntityRepository $r) {
                    return $r->createQueryBuilder("c")
                        ->where("c.parent_category is NULL")
                        ->andWhere("c.is_hidden = false")
                        ->orderBy("c.name", "ASC");
                },
                "property" => "name",
                'label' => 'Categories',
                'empty_value' => 'Select category',
                "multiple" => true,
                "configs" => array(
                    "width" => '100%',
                ),
            ))
                ->addViewTransformer($cat_transformer)
        );

        if($options["type"] != "edit"){
            $builder->add("image_file", "file", ["required" => false]);
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolverInterface)
    {
        $resolverInterface->setDefaults([
                "data_class" => 'Zgh\FEBundle\Entity\Tip',
                "cascade_validation" => true,
                "type" => null
            ]);
    }

    public function getName()
    {
        return "tip";
    }
}