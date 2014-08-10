<?php
namespace Zgh\FEBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Zgh\FEBundle\Repository\UserRepository;

class VendorIntroType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add("company_name", "text")
            ->add("categories", "thrace_select2_entity", [
                    "class" => 'Zgh\FEBundle\Entity\Category',
                    "property" => "name",
                    'label' => 'Categories',
                    'empty_value' => 'Select category',
                    "multiple" => true,
                    "configs" => array(
                        "width" => '100%',
                    ),
                ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                "data_class" => 'Zgh\FEBundle\Entity\VendorInfo',
                "validation_groups" => ["vendor_intro"],
                "cascade_validation" => true,
            ));
    }

    public function getName()
    {
        return "vendor_intro";
    }
}