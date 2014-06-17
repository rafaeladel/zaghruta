<?php
namespace Zgh\FEBundle\TwigExtension;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;
use Zgh\FEBundle\Service\Paginator;

class PaginatorExtension extends \Twig_Extension
{
    /**
     * @var \Zgh\FEBundle\Service\Paginator
     */
    protected $paginator;

    /**
     * @var Twig_Environment
     */
    protected $env;

    public function initRuntime(Twig_Environment $environment)
    {
        $this->env = $environment;
    }


    public function __construct(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction("getNext", [$this, "getNext"]),
            new \Twig_SimpleFunction("getPrev", [$this, "getPrev"]),
            new \Twig_SimpleFunction("nextId", [$this, "nextId"]),
            new \Twig_SimpleFunction("prevId", [$this, "prevId"])
        ];
    }

    public function nextId(Collection $collection, $element)
    {
        $nextObj = $this->getNext($collection, $element);
        $nextId = $nextObj != null ? $nextObj->getId() : null;
        return $nextId;
    }

    public function prevId(Collection $collection, $element)
    {
        $prevObj = $this->getPrev($collection, $element);
        $prevId = $prevObj != null ? $prevObj->getId() : null;
        return $prevId;
    }

    public function getNext(Collection $collection, $element)
    {
        return $this->paginator->getNext($collection, $element);
    }

    public function getPrev(Collection $collection, $element)
    {
        return $this->paginator->getPrev($collection, $element);
    }

    public function getName()
    {
        return "zgh_paginator";
    }
}