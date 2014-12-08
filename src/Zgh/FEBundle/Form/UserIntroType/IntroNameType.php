<?php
namespace Zgh\FEBundle\Form\UserIntroType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IntroNameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("firstname", "text", [
                "attr" => [
                    "maxlength" => 65
                ]
            ])
            ->add("lastname", "text", [
                "attr" => [
                    "maxlength" => 65
                ]
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            "data_class" => "Zgh\FEBundle\Entity\User",
        ]);
    }

    public function getName()
    {
        return "intro_name_type";
    }
}