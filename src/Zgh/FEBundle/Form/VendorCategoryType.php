<?php
namespace Zgh\FEBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VendorCategoryType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add("categories", "entity", array(
                    "class" => "ZghFEBundle:Category",
                    "property" => "name",
                    "multiple" => true,
                    "expanded" => true
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                "data_class" => 'Zgh\FEBundle\Entity\VendorInfo',
                "cascade_validation" => true,
            ));
    }

    public function getName()
    {
        return "category";
    }
}