<?php
namespace Zgh\FEBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", "text")
            ->add("description", "textarea", ["required" => false])
            ->add("category", "thrace_select2_entity", array(
                    "class" => 'Zgh\FEBundle\Entity\Category',
                    "property" => "name",
                    'label' => 'Categories',
                    'empty_value' => 'Select category',
                    "configs" => array(
                        "width" => '100%',
                    ),
                ))
            ->add("price", "number", ["required" => false])
        ;

        if($options["type"] == "add"){
            $builder->add("image_file", "file", ["required" => false]);
        }

        $builder->add("tags", "tag_input", ["required" => false]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                "data_class" => 'Zgh\FEBundle\Entity\Product',
                "type" => null,
//                "cascade_validation" => true,
            ));
    }

    public function getName()
    {
        return "product";
    }
}