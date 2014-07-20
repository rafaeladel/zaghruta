<?php
namespace Zgh\FEBundle\Form;

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class RegistrationType extends BaseType
{
   public function buildForm(FormBuilderInterface $builder, array $options)
   {
       parent::buildForm($builder, $options);
       $builder->remove("username");
   }

   public function setDefaultOptions(OptionsResolverInterface $resolver)
   {
       $resolver->setDefaults(array(
               "data_class" => 'Zgh\FEBundle\Entity\User'
               //Validation groups is configured in config.yml
           ));
   }

   public function getName()
   {
       return "zgh_fe_registration_form_type";
   }
}