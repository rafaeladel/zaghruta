<?php
namespace Zgh\FEBundle\Transformer;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ImageToEntityTransformer implements DataTransformerInterface
{
//    protected $em;
//    public function __construct(EntityManager $em)
//    {
//        $this->em = $em;
//    }

    public function transform($img)
    {
        if($img === null)
        {
            return null;
        }
        return $img->getImageFile();
    }

    public function reverseTransform($file)
    {
        if($file == null)
        {
            return null;
        }

    }
}