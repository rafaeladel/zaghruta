<?php
namespace Zgh\FEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SettingsController extends Controller
{
    public function getSettingsAction()
    {
        $user = $this->get("security.context")->getToken()->getUser();
        return $this->render("@ZghFE/Default/settings.html.twig", array("user" => $user));
    }

    public function postBasicInfoAction(Request $request)
    {
        $user = $user = $this->get("security.context")->getToken()->getUser();
        $f_name = $request->request->get("firstname");
        $l_name = $request->request->get("lastname");
        $user->setFirstname($f_name);
        $user->setLastname($l_name);
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();
        $this->get("session")->getFlashBag()->add("notice", "Saved!");
        return $this->redirect($this->generateUrl("zgh_fe.settings.getSettings", array(
                    "id" => $user->getId()
                )));
    }
}