<?php
namespace Zgh\FEBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("search_text", "text")
            ->add("search_filter", "entity", [
                    "class" => "Zgh\FEBundle\Entity\Category",
                    "query_builder" => function(EntityRepository $er){
                            return $er->createQueryBuilder("c");
                        },
                    "property" => "name",
                    "required" => false,
                    "multiple" => true,
                    "expanded" => true
                ])
            ->add("search_sort", "choice", [
                    "choices" => [
                        "ASC" => "Ascending",
                        "DESC" => "Descending"
                    ],
                    "data" => 1,
                    "required" => false,
                    "expanded" => true
                ])
            ->add("search_type", "hidden")
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
                "data_class" => "Zgh\FEBundle\Entity\Search"
            ]);
    }

    public function getName()
    {
        return "search";
    }
}