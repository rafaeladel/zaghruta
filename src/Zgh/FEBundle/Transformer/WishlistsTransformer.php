<?php
namespace Zgh\FEBundle\Transformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Zgh\FEBundle\Entity\Wishlist;

class WishlistsTransformer implements  DataTransformerInterface
{
    private $em;
    private $security_context;

    public function __construct(EntityManagerInterface $em, SecurityContextInterface $contextInterface )
    {
        $this->em = $em;
        $this->security_context = $contextInterface;
    }

    //From DB to View
    public function transform($wishlists)
    {
        //If returned "", Exception : Expected a Doctrine\Common\Collections\Collection object. Is generated.
        if(!($wishlists instanceof Wishlist)){
            return [];
        }

        return $wishlists->toArray();
//        $wishlists_arr = [];
//        foreach ($wishlists as $wishlist) {
//            $wishlists_arr[] = $wishlist->getName();
//        }
//        return implode(", ", $wishlists_arr);
    }

    //From View To DB
    public function reverseTransform($wishlists)
    {
        if($wishlists == null){
            return [];
        }

        $entity_arr = [];
        $current_user = $this->security_context->getToken()->getUser();
        foreach($wishlists as $wishlist){
            $entity = $this->em->getRepository("ZghFEBundle:Wishlist")->findOneByName($wishlist);
            if($entity == null)
            {
                $entity = new Wishlist();
                $entity->setName($wishlist);
                $entity->setUser($current_user);
            }
            $entity_arr[] = $entity;
        }
        $collection = new ArrayCollection($entity_arr);
        return $collection;
    }
}