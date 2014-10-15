<?php

namespace Zgh\FEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Zgh\FEBundle\Entity\User;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ZghFEBundle:Default:index.html.twig', array('name' => $name));
    }

    public function getUserLoginPartialAction(Request $request)
    {
        $session = $request->getSession();
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

        $csrfToken = $this->container->has('form.csrf_provider')
            ? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate')
            : null;
        return $this->render("@ZghFE/Partial/security/userLoginPartial.html.twig", array(
            "last_username" => $lastUsername,
            "csrf_token" => $csrfToken
        ));
    }

    public function getVendorLoginPartialAction(Request $request)
    {
        $session = $request->getSession();
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

        $csrfToken = $this->container->has('form.csrf_provider')
            ? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate')
            : null;
        return $this->render("@ZghFE/Partial/security/vendorLoginPartial.html.twig", array(
            "last_username" => $lastUsername,
            "csrf_token" => $csrfToken
        ));
    }

    public function getShortcutsAndNotificationAction(Request $request)
    {
        $user = $this->getUser();
        $logged_in = true;
        if (!$user instanceof User) {
            $logged_in = false;
        }
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                "logged_in" => $logged_in,
                "view" => $this->renderView("@ZghFE/Partial/common/shortcuts&notifications.html.twig")
            ]);
        } else {
            return $this->render("@ZghFE/Partial/common/shortcuts&notifications.html.twig");
        }
    }
}
