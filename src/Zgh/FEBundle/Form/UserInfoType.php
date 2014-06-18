<?php
namespace Zgh\FEBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Zgh\FEBundle\Repository\UserRepository;

class UserInfoType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add("birthday", "birthday")
            ->add("gender", "choice", array("choices" => array("0" => "Male", "1" => "Female")))
            ->add("status", "choice", array("choices" => array(
                    "Single" => "Single",
                    "In relationship" => "In relationship",
                    "Engaged" => "Engaged",
                    "Married" => "Married"
                )))
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
                    ]
                ])
            ->add("job", "text")
            ->add("facebook", "url")
            ->add("twitter", "url")
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
            $form = $event->getForm();
            $user_info = $event->getData();
            $user = $user_info->getUser();

            $display = $user_info->getStatus() == "Single" ? "none" : "block";

            $form->add("relationship_user", "thrace_select2_entity", array(
                    "class" => 'Zgh\FEBundle\Entity\User',
                    "query_builder" => function(UserRepository $repository) use ($user){
                          return $repository->getUsersForRelationship($user);
                        },
                    'label' => 'User',
                    "property" => "fullname",
                    'empty_value' => 'Select user',
                    "configs" => array(
                        "width" => '100%',
                        'minimumInputLength' => 3,
                    ),
                    "attr" => array(
                        "style" => "display:".$display.";padding-top:5px;",
                    )
                ));
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                "data_class" => 'Zgh\FEBundle\Entity\UserInfo',
                "cascade_validation" => true,
            ));
    }

    public function getName()
    {
        return "user_info";
    }
}