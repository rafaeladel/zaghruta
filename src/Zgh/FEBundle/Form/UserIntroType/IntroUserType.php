<?php
namespace Zgh\FEBundle\Form\UserIntroType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IntroUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", new IntroNameType())
            ->add("info", new IntroInfoType())
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            "data_class" => null,
            "cascade_validation" => true,
            "validation_groups" => ["intro"]
        ]);
    }

    public function getName()
    {
        return "intro_user_type";
    }
}