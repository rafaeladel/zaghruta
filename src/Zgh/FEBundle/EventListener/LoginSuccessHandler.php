<?php

namespace Zgh\FEBundle\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $router;
    private $session;

    public function __construct(UrlGeneratorInterface $router, Session $session)
    {
        $this->router = $router;
        $this->session = $session;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $user = $token->getUser();
        if($user->getFirstTime() == true)
        {

            if(in_array("ROLE_FACEBOOK", $user->getRoles()))
            {
                return new RedirectResponse($this->router->generate("zgh_fe.wall.index"));
            }
            return new RedirectResponse($this->router->generate("zgh_fe.user_profile.user_intro_edit", array(
                    "id" => $user->getId()
                )));
        }
        if($this->session->has("_security.main.target_path")) {
            return new RedirectResponse($this->session->get("_security.main.target_path"));
        }
        return new RedirectResponse($this->router->generate("zgh_fe.wall.index"));
    }
}