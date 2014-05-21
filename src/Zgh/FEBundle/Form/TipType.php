<?php
namespace Zgh\FEBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TipType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add("title", "text")
            ->add("content", "textarea")
            ->add("image_file", "file", ["required" => false])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolverInterface)
    {
        $resolverInterface->setDefaults([
                "data_class" => 'Zgh\FEBundle\Entity\Tip',
                "cascade_validation" => true
            ]);
    }

    public function getName()
    {
        return "tip";
    }
}