<?php
namespace Zgh\FEBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ExperienceType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add("title", "text")
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
            ->add("content", "textarea")
            ->add("image_file", "file", array(
                "required" => false,
                "attr" => array(
                    "class" => "photo_browse"
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                "data_class" => 'Zgh\FEBundle\Entity\Experience',
                "cascade_validation" => true,
            ));
    }

    public function getName()
    {
        return "experience";
    }
}