<?php
namespace Zgh\FEBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add("name", "text")
            ->add("description", "textarea", ["required" => false])
            ->add("categories", "thrace_select2_entity", array(
                    "class" => 'Zgh\FEBundle\Entity\Category',
                    "property" => "name",
                    'label' => 'Categories',
                    'multiple' => true,
                    'empty_value' => 'Select category',
                    "configs" => array(
                        "width" => '100%',
                    ),
                ))
            ->add("price", "number", ["required" => false])
            ->add("image_file", "file", ["required" => false])
            ->add("tags", "tag_input", ["required" => false])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                "data_class" => 'Zgh\FEBundle\Entity\Product',
                "cascade_validation" => true,
            ));
    }

    public function getName()
    {
        return "product";
    }
}