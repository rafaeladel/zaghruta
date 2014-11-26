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
            ->add("city", "thrace_select2_choice", [
                    "empty_value" => "City",
                    "choices" => [
                        "Alexandria" => "Alexandria",
                        "Aswan" => "Aswan",
                        "Asyut" => "Asyut",
                        "Beheira" => "Beheira",
                        "Beni Suef" => "Beni Suef",
                        "Cairo" => "Cairo",
                        "Dakahlia" => "Dakahlia",
                        "Damietta" => "Damietta",
                        "Faiyum" => "Faiyum",
                        "Gharbia" => "Gharbia",
                        "Giza" => "Giza",
                        "Ismailia" => "Ismailia",
                        "Kafr el-Sheikh" => "Kafr el-Sheikh",
                        "Matruh" => "Matruh",
                        "Minya" => "Minya",
                        "Monufia" => "Monufia",
                        "New Valley" => "New Valley",
                        "North Sinai" => "North Sinai",
                        "Port Said" => "Port Said",
                        "Qalyubia" => "Qalyubia",
                        "Qena" => "Qena",
                        "Red Sea" => "Red Sea",
                        "Al Sharqia" => "Al Sharqia",
                        "Sohag" => "Sohag",
                        "South Sinai" => "South Sinai",
                        "Suez" => "Suez",
                        "Luxor" => "Luxor",
                        "6th of October" => "6th of October",
                        "Tanta" => "Tanta"
                    ],
                    "configs" => [
                        "width" => "100%"
                    ],
                    "required" => false
                ])
            ->add("phone", "text", [
                "required" => false,
                "invalid_message" => "Only Numbers Allowed"
            ])
            ->add("mobile", "text", [
                "required" => false,
                "invalid_message" => "Only Numbers Allowed"
            ])
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