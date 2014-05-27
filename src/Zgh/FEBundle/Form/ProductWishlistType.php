<?php
namespace Zgh\FEBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Zgh\FEBundle\Transformer\WishlistTransformer;

class ProductWishlistType extends AbstractType
{
    private $security_context;
    public function __construct(SecurityContextInterface $contextInterface)
    {
        $this->security_context = $contextInterface;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new WishlistTransformer($this->security_context);

        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                $user = $this->security_context->getToken()->getUser();
                $form = $event->getForm();

                $form->add("wishlists", "entity", [
                    "class" => "Zgh\FEBundle\Entity\Wishlist",
                    "choices" => $user->getWishlists(),
                    "property" => "name",
                    "multiple" => true,
                    "expanded" => true
                ]);
            })
        ;

        $builder->add("new_wishlist", new ProductWishlistNewType($this->security_context),[
            "mapped" => false
        ]);

//        $builder->add(
//            $builder->create("wishlists", "text")
//                    ->addModelTransformer($transformer)
//        );

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            "data_class" => "Zgh\FEBundle\Entity\Product",
            "cascade_validation" => true
        ]);
    }


    public function getName()
    {
        return "product_wishlist";
    }
}