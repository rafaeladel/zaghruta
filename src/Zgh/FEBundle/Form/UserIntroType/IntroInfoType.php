<?php
namespace Zgh\FEBundle\Form\UserIntroType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class IntroInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("birthday", "date", [
                    "years" => range(1950, date("Y")),
                    "data" => new \DateTime("1990-01-01")
                ])
            ->add("gender", "choice",[
                "expanded" => true,
                "choices" => array(
                    "0" => "Male",
                    "1" => "Female"
                )
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            "data_class" => "Zgh\FEBundle\Entity\UserInfo",
        ]);
    }

    public function getName()
    {
        return "intro_info_type";
    }
}