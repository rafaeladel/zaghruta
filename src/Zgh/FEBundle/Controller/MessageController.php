<?php
namespace Zgh\FEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MessageController extends Controller
{
    public function getListAction()
    {
        return $this->render("@ZghFE/Default/messages.html.twig");
    }
}