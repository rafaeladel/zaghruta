<?php
namespace Zgh\FEBundle\Transformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Zgh\FEBundle\Entity\Tag;

class TagsTransformer implements  DataTransformerInterface
{
    private $em;
    private $context;

    public function __construct(EntityManagerInterface $em, SecurityContextInterface $contextInterface)
    {
        $this->em = $em;
        $this->context = $contextInterface;
    }

    //From DB to View
    public function transform($tags)
    {
        if(!$tags instanceof \ArrayAccess){
            return [];
        }

        $tags_arr = [];
        foreach ($tags as $tag) {
            $tags_arr[] = $tag->getName();
        }
        return $tags_arr;
    }

    //From View To DB
    public function reverseTransform($tags)
    {
        if($tags == null){
            return [];
        }

        $entity_arr = [];
        foreach($tags as $tag){
            $entity = $this->em->getRepository("ZghFEBundle:Tag")->findOneByName($tag);
            if($entity == null)
            {
                $entity = new Tag();
                $entity->setName($tag);
                $entity->addUser($this->context->getToken()->getUser());
            }
            $entity_arr[] = $entity;
        }
        $collection = new ArrayCollection($entity_arr);
        return $collection;
    }
}