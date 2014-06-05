<?php
namespace Zgh\FEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NotificationController extends Controller
{
    public function getListAction()
    {
        $user = $this->getUser();
        $new_notifications = $this->getDoctrine()->getRepository("ZghFEBundle:Notification")->getNewNotifications($user);
        foreach ($new_notifications as $notification) {
            $notification->setIsRead(true);
            $this->getDoctrine()->getManager()->persist($notification);
        }
        $this->getDoctrine()->getManager()->flush();
        $notifications = $user->getNotifications();
        return $this->render("ZghFEBundle:Default:notifications.html.twig",[
            "notifications" => $notifications
        ]);
    }
}