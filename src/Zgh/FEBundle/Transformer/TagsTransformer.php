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
        if(!($tags instanceof Tag)){
            return "";
        }

        $tags_arr = [];
        foreach ($tags as $tag) {
            $tags_arr[] = $tag->getName();
        }
        return implode(", ", $tags_arr);
    }

    //From View To DB
    public function reverseTransform($tags)
    {
        if(count(trim($tags)) == 0){
            return new ArrayCollection();
        }

        $tags_arr = array_filter(array_map("trim", explode(",", $tags)));
        $entity_arr = [];
        foreach($tags_arr as $tag_name){
            $entity = $this->em->getRepository("ZghFEBundle:Tag")->findOneByName($tag_name);
            if($entity == null)
            {
                $entity = new Tag();
                $entity->setName($tag_name);
            }
            $entity_arr[] = $entity;
        }
        $collection = new ArrayCollection($entity_arr);
        return $collection;
    }
}