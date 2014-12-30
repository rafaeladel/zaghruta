<?php

namespace Zgh\MsgBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ZghMsgBundle:Default:index.html.twig', array('name' => $name));
    }
}
