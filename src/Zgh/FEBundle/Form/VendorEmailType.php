<?php
namespace Zgh\FEBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class VendorEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add("new_email", "email")
            ->add("current_password", "password", [
                'label' => 'form.current_password',
                'translation_domain' => 'FOSUserBundle',
                'mapped' => false,
                'constraints' => new UserPassword([
                    "groups" => ["change_email"]
                ])
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => 'Zgh\FEBundle\Entity\User',
            "cascade_validation" => true,
            "validation_groups" => ["change_email"]
        ));
    }

    public function getName()
    {
        return "vendor_email_type";
    }
}