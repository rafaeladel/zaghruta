<?php
namespace Zgh\FEBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InterestType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add("interests", "entity", array(
                    "class" => "ZghFEBundle:Category",
                    "query_builder" => function(EntityRepository $r) {
                            return $r->createQueryBuilder("c")
                                        ->where("c.is_hidden = :hidden")
                                        ->setParameter("hidden", false)
                                        ->orderBy("c.name", "ASC");
                    },
                    "property" => "name",
                    "multiple" => true,
                    "expanded" => true
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                "data_class" => 'Zgh\FEBundle\Entity\User',
                "cascade_validation" => true,
            ));
    }

    public function getName()
    {
        return "interest";
    }
}