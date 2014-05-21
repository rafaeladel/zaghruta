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
       $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
            $entity = $event->getData();
            $form = $event->getForm();
//               var_dump($entity->getRoles());
//               die;
            if(in_array("ROLE_VENDOR", $entity->getRoles()))
            {
                $form->add("entity_type", "hidden", array("mapped" => false, "attr" => array("value" => "ROLE_VENDOR")));
            } else if(in_array("ROLE_CUSTOMER", $entity->getRoles())){
                $form->add("entity_type", "hidden", array("mapped" => false, "attr" => array("value" => "ROLE_CUSTOMER")));
            }
        });
   }

   public function setDefaultOptions(OptionsResolverInterface $resolver)
   {
       $resolver->setDefaults(array(
               "data_class" => 'Zgh\FEBundle\Entity\User',
               "validation_groups" => array("Registration")
           ));
   }

   public function getName()
   {
       return "zgh_fe_registration_form_type";
   }
}