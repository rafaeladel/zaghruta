<?php
namespace Zgh\FEBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TipType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("title", "text")
            ->add("content", "textarea");

        if($options["type"] != "edit"){
            $builder->add("image_file", "file", ["required" => false]);
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolverInterface)
    {
        $resolverInterface->setDefaults([
                "data_class" => 'Zgh\FEBundle\Entity\Tip',
                "cascade_validation" => true,
                "type" => null
            ]);
    }

    public function getName()
    {
        return "tip";
    }
}