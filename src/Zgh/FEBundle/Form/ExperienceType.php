<?php
namespace Zgh\FEBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ExperienceType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("title", "text")
            ->add("category", "thrace_select2_entity", array(
                    "class" => 'Zgh\FEBundle\Entity\Category',
                    "property" => "name",
                    'label' => 'Categories',
                    'empty_value' => 'Select category',
                    "configs" => array(
                        "width" => '100%',
                    ),
                ))
            ->add("content", "textarea");

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