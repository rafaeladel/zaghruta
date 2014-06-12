<?php
namespace Zgh\FEBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BranchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("address", "text")
            ->add("city", "text")
            ->add("phone", "number", ["required" => false])
            ->add("mobile", "number", ["required" => false])
            ->add("email", "email", ["required" => false])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
                "data_class" => "Zgh\FEBundle\Entity\Branch",
                "cascade_validation" => true
            ]);
    }

    public function getName()
    {
        return "branch";
    }
}