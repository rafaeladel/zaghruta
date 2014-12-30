<?php
namespace Zgh\FEBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Zgh\FEBundle\Repository\UserRepository;

class VendorInfoType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add("company_name", "text", [
                "attr" => [
                    "maxlength" => 40
                ]
            ])
            ->remove("categories")
            ->add("mobile", "text", ["required" => false])
            ->add("website", "url", ["required" => false])
            ->add("email", "email", ["required" => false])
            ->add("facebook", "url", ["required" => false])
            ->add("twitter", "url", ["required" => false])
            ->add("info", "textarea", ["required" => false])
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
        return "vendor_info";
    }
}