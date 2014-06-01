<?php
namespace Zgh\FEBundle\Form\Type;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Zgh\FEBundle\Transformer\WishlistsTransformer;

class WishlistInputType extends AbstractType
{
    private $em;
    private $router;
    private $security_context;

    public function __construct(RouterInterface $routerInterface, EntityManagerInterface $entityManagerInterface, SecurityContextInterface $contextInterface)
    {
        $this->em = $entityManagerInterface;
        $this->router = $routerInterface;
        $this->security_context = $contextInterface;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new WishlistsTransformer($this->em, $this->security_context);
        $builder->addModelTransformer($transformer);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            "empty_value" => "Wishlist",
            "multiple" => true,
            "configs" => [
                'width' => '100%',
                'ajax' => [
                    'url' => $this->router->generate('zgh_fe.wishlists.serialized', array(), true),
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
        return "wishlist_input";
    }
}