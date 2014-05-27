<?php
namespace Zgh\FEBundle\Transformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Zgh\FEBundle\Entity\Wishlist;

class WishlistTransformer implements DataTransformerInterface
{
    private $security_context;

    public function __construct(SecurityContextInterface $contextInterface)
    {
        $this->security_context = $contextInterface;
    }

    public function transform($value)
    {
        return '';
    }

    public function reverseTransform($value)
    {
        if(count(trim($value)) == 0)
        {
            return null;
        }
        $user = $this->security_context->getToken()->getUser();
        $wishlist = new Wishlist();
        $wishlist->setUser($user);
        $wishlist->setName($value);
        return new ArrayCollection([$wishlist]);
    }
}