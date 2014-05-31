<?php
namespace Zgh\FEBundle\Form\Type;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Zgh\FEBundle\Transformer\TagsTransformer;

class TagInputType extends AbstractType
{
    private $em;
    private $container;

    public function __construct(ContainerInterface $containerInterface, EntityManagerInterface $entityManagerInterface)
    {
        $this->em = $entityManagerInterface;
        $this->container = $containerInterface;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new TagsTransformer($this->em);
        $builder->addModelTransformer($transformer);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            "empty_value" => "Tag",
            "multiple" => true,
            "configs" => [
                'width' => '100%',
                'ajax' => [
                    'url' => $this->container->get('router')->generate('zgh_fe.tags.serialized', array(), true),
                    'type' => 'GET',
                    'dataType' => 'json',
                    'data' => "function (term, page) {
                        return {
                            q: term, //search term
                            page_limit: 5, // page size
                            page: page, // page number
                        };
                    }",
                    'results' => "function (data, page) {
                        return {results: data};
                    }",
                ],
                "createSearchChoice" => "function(term, data) {
                    if ($(data).filter(function() {
                            return this.text.localeCompare(term) === 0;
                        }).length === 0) {
                        return {
                            id: term,
                            text: term
                        };
                    }
                }",
            ]
        ]);
    }

    public function getParent()
    {
        return "thrace_select2_ajax";
    }

    public function getName()
    {
        return "tag_input";
    }
}