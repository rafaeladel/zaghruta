<?php
namespace Zgh\FEBundle\Form\Type;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Zgh\FEBundle\Transformer\TagsTransformer;

class TagInputType extends AbstractType
{
    private $em;
    private $router;
    private $context;

    public function __construct(RouterInterface $routerInterface, EntityManagerInterface $entityManagerInterface, SecurityContextInterface $contextInterface)
    {
        $this->em = $entityManagerInterface;
        $this->router = $routerInterface;
        $this->context = $contextInterface;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new TagsTransformer($this->em, $this->context);
        $builder->addModelTransformer($transformer);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            "empty_value" => "Tag",
            "multiple" => true,
            "configs" => [
                'width' => '100%',
                "maximumInputLength" => "30",
                'ajax' => [
                    'url' => $this->router->generate('zgh_fe.tags.serialized', array(), true),
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