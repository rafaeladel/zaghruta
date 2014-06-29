<?php
namespace Zgh\FEBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Zgh\FEBundle\Transformer\MultiCategoryTransformer;

class ExperienceType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $cat_transformer = new MultiCategoryTransformer();
        $builder
            ->add("title", "text");
        $builder->add(
            $builder->create("categories", "thrace_select2_entity", array(
                    "class" => 'Zgh\FEBundle\Entity\Category',
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
//            ->add("categories", "thrace_select2_entity", array(
//                    "class" => 'Zgh\FEBundle\Entity\Category',
//                    "property" => "name",
//                    'label' => 'Categories',
//                    'empty_value' => 'Select category',
//                    "multiple" => true,
//                    "configs" => array(
//                        "width" => '100%',
//                    ),
//                ))
        $builder->add("content", "textarea");

        if($options["type"] != "edit"){
            $builder->add("image_file", "file", ["required" => false]);
        }

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                "data_class" => 'Zgh\FEBundle\Entity\Experience',
                "cascade_validation" => true,
                "type" => null
            ));
    }

    public function getName()
    {
        return "experience";
    }
}