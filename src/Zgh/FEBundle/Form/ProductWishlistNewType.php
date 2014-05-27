<?php
namespace Zgh\FEBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Zgh\FEBundle\Transformer\WishlistTransformer;

class ProductWishlistNewType extends AbstractType
{
    private $security_context;
    public function __construct(SecurityContextInterface $contextInterface)
    {
        $this->security_context = $contextInterface;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new WishlistTransformer($this->security_context);
        $builder->add(
            $builder->create("wishlists", "text")
                ->addModelTransformer($transformer)
        );

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            "data_class" => "Zgh\FEBundle\Entity\Product"
        ]);
    }


    public function getName()
    {
        return "product_wishlist_new";
    }
}