<?php
namespace Zgh\FEBundle\Form\Type;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Zgh\FEBundle\Transformer\TagsTransformer;

class TagInputType extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->em = $entityManagerInterface;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $transformer = new TagsTransformer($this->em);
//        $builder->addModelTransformer($transformer);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
                "class" => "Zgh\FEBundle\Entity\Tag",
                "property" => "name",
                "empty_value" => "Tag",
                "multiple" => true
            ]);
    }

    public function getParent()
    {
        return "thrace_select2_entity";
    }

    public function getName()
    {
        return "tag_input";
    }
}