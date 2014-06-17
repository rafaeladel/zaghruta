<?php
namespace Zgh\FEBundle\Service;

use Doctrine\Common\Collections\Collection;

class Paginator
{
    protected $element;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $array;

    protected $arrayLength;


    public function init(Collection $array, $entity)
    {
        $this->array = $array;
        $this->element = $entity;
        $this->arrayLength = count($array);
    }

    public function getNext($array, $entity)
    {
        $this->init($array, $entity);
        $currentIndex = $this->array->indexOf($this->element);
        $nextObj = $this->array->get($currentIndex + 1);
        return $nextObj;
    }

    public function getPrev($array, $entity)
    {
        $this->init($array, $entity);
        $currentIndex = $this->array->indexOf($this->element);
        $prevObj = $this->array->get($currentIndex - 1);
        return $prevObj;
    }
}