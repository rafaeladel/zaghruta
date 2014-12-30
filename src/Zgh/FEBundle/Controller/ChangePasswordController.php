<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zgh\FEBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Controller managing the password change
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class ChangePasswordController extends ContainerAware
{
    /**
     * Change user password
     */
    public function changePasswordAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::CHANGE_PASSWORD_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.change_password.form.factory');

        $form = $formFactory->createForm();
        $form->setData($user);


        if ($request->isMethod('POST')) {

            $form->bind($request);

            if ($form->isValid()) {

                /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
                $userManager = $this->container->get('fos_user.user_manager');

                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::CHANGE_PASSWORD_SUCCESS, $event);

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->container->get('router')->generate('zgh_fe.wall.index');
                    if($request->isXmlHttpRequest()) {
                        $response = new JsonResponse([
                            "success" => true,
                            "message" => "Password Changed!"
                        ]);
                    } else {
                        $this->container->get("session")->getFlashBag()->add("password_notice", "Password changed!");
                        $response = new RedirectResponse($this->container->get("router")->generate("zgh_fe.settings.getSettings", array(
                                "id" => $user->getId(),
                            )
                        ));
                    }
                }

                $dispatcher->dispatch(FOSUserEvents::CHANGE_PASSWORD_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }
            else {
                if($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        "success" => false,
                        "message" => "Current password is incorrect."
                    ]);
                } else {
                    $this->container->get("session")->getFlashBag()->add("password_error", "Error while changing password. Please make sure the current password is correct. And password verification matches.");
                    return new RedirectResponse($this->container->get("router")->generate("zgh_fe.settings.getSettings", array(
                            "id" => $user->getId(),
                        )
                    ));
                }
            }
        }

        return $this->container->get('templating')->renderResponse(
            'ZghFEBundle:ChangePassword:changePassword_content.html.'.$this->container->getParameter('fos_user.template.engine'),
            array('form' => $form->createView())
        );
    }
}
