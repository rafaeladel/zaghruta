<?php
namespace Zgh\FEBundle\Transformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Zgh\FEBundle\Entity\Tag;

class TagsTransformer implements  DataTransformerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    //From DB to View
    public function transform($tags)
    {
        //If returned "", Exception : Expected a Doctrine\Common\Collections\Collection object. Is generated.
        if(!($tags instanceof Tag)){
            return [];
        }

        return $tags->toArray();
//        $tags_arr = [];
//        foreach ($tags as $tag) {
//            $tags_arr[] = $tag->getName();
//        }
//        return implode(", ", $tags_arr);
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
            }
            $entity_arr[] = $entity;
        }
        $collection = new ArrayCollection($entity_arr);
        return $collection;
    }
}