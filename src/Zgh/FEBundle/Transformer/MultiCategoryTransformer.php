<?php
namespace Zgh\FEBundle\Transformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Zgh\FEBundle\Entity\Tag;

/**
 * Class MultiCategoryTransformer
 *
 * Used to transform data returned from select2 into appropriate array
 * comma separated string to array
 *
 * @package Zgh\FEBundle\Transformer
 */
class MultiCategoryTransformer implements  DataTransformerInterface
{

    public function __construct()
    {
    }

    //From DB to View
    public function transform($tags)
    {
        return $tags;
//        if(!$tags instanceof \ArrayAccess){
//            return [];
//        }
//
//        $tags_arr = [];
//        foreach ($tags as $tag) {
//            $tags_arr[] = $tag->getName();
//        }
//        return $tags_arr;
    }

    //From View To DB
    public function reverseTransform($tags)
    {

        if($tags == null){
            return [];
        }

        if(is_array($tags)) {
            if(count($tags) == 1) {
                if(!preg_match("/[a-zA-Z0-9],[a-zA-Z0-9]/", $tags[0])) {
                    return $tags;
                }
            } elseif(count($tags) > 1) {
                return $tags;
            }
        }

        $values = explode(",", $tags[0]);
        $result = [];
        foreach($values as $value) {
            $result[] = $value;
        }

        return new ArrayCollection($result);
//        var_dump($tags);
//        die;
//
//        $entity_arr = [];
//        foreach($tags as $tag){
//            $entity = $this->em->getRepository("ZghFEBundle:Tag")->findOneByName($tag);
//            if($entity == null)
//            {
//                $entity = new Tag();
//                $entity->setName($tag);
//            }
//            $entity_arr[] = $entity;
//        }
//        $collection = new ArrayCollection($entity_arr);
//        return $collection;
    }
}