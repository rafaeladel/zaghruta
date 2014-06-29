<?php
namespace Zgh\FEBundle\Transformer;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;

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
//        var_dump(1);
//        die;
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

        return new ArrayCollection($values);
    }
}