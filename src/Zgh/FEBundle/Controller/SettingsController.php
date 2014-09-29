<?php
namespace Zgh\FEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Zgh\FEBundle\Entity\User;
use Zgh\FEBundle\Form\VendorEmailType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class SettingsController extends Controller
{
    public function getSettingsAction()
    {
        $user = $this->get("security.context")->getToken()->getUser();
        $email_form = $this->createForm(new VendorEmailType(), $user);
        return $this->render("@ZghFE/Default/settings.html.twig", array(
            "user" => $user,
            "email_form" => $email_form->createView()
        ));
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

    public function postChangeEmailAction(Request $request)
    {
        $user = $this->getUser();
        $email_form = $this->createForm(new VendorEmailType(), $user);
        $email_form->handleRequest($request);
        if($email_form->isValid())
        {
            $token_generator = $this->get("fos_user.util.token_generator");
            $user->setNewEmailToken($token_generator->generateToken());
            $this->get("fos_user.user_manager")->updateUser($user);
            $conf_email = $this->generateUrl("zgh_fe.settings.activate_email", ["token" => $user->getNewEmailToken() ]);
            $this->get("zgh_fe.email_notifier")->sendEmailChangeConfirmation($user, $conf_email);
            $this->get("session")->getFlashBag()->add("email_notice", "Check your old email {$user->getEmail()} for email change confirmation.");
            return new RedirectResponse($this->generateUrl("zgh_fe.settings.getSettings"));
        }
        else
        {
            return $this->render("@ZghFE/Default/settings.html.twig", array(
                "user" => $user,
                "email_form" => $email_form->createView()
            ));
        }
    }

    /**
     * @param $token
     * @internal param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activateEmailAction($token)
    {
        $user = $this->getDoctrine()->getRepository("ZghFEBundle:User")->findOneBy([
            "new_email_token" =>  $token
        ]);
        if(!$user) {
            throw new NotFoundHttpException();
        }
        $user->setEmail($user->getNewEmail());
        $user->setNewEmail(null);
        $user->setNewEmailToken(null);
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush($user);
        return new RedirectResponse($this->generateUrl("zgh_fe.wall.index"));
    }

}