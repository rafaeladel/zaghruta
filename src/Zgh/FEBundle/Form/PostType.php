<?php
namespace Zgh\FEBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add("content", "textarea", [
                    "required" => true
                ])
            ->add("post_image", "file", array(
                    "mapped" => false,
                    "required" => false,
                    "attr" => array(
                        "accept" => "image/*"
                    )
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                "data_class" => 'Zgh\FEBundle\Entity\Post',
                "cascade_validation" => true,
            ));
    }

    public function getName()
    {
        return "post";
    }
}