<?php

namespace Zgh\FEBundle\Security\User\Provider;

use FOS\UserBundle\Model\User;
use FOS\UserBundle\Model\UserManagerInterface;
use HWI\Bundle\OAuthBundle\Connect\AccountConnectorInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\Exception\AccountNotLinkedException;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Zgh\FEBundle\Entity\UserCP;
use Zgh\FEBundle\Entity\UserInfo;
use Zgh\FEBundle\Entity\UserPP;
use Zgh\FEBundle\Utility\PhotoInitializer;

class ZaghrutaUserProvider extends FOSUBUserProvider
{
    /**
     * @var \Symfony\Component\HttpKernel\Kernel
     */
    protected $kernel;

    public function __construct(UserManagerInterface $userManager, array $properties, Kernel $kernel)
    {
        parent::__construct($userManager, $properties);
        $this->kernel = $kernel;
    }

    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        //property (e.g. facebook_id)
        $property = $this->getProperty($response);
        $username = $response->getUsername();

        //Getting resource owner (e.g. facebook, google, etc..)
        $service = $response->getResourceOwner()->getName();
        //Getting setters names in User entity
        $setter = 'set' . ucfirst($service);
        $setter_id = $setter . 'Id';
        $setter_token = $setter . 'AccessToken';

        //Checking if a previous user is already connected. If so, reset its id and token to null
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }

        //Connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());

        $this->userManager->updateUser($user);
    }

    //Check if the user trying to connect is in the DB in the first place or not.
    //If not, Create it.
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();

        $user = $this->userManager->findUserBy(array($property => $username));
        if ($user === null) {
            $user = $this->userManager->findUserByEmail($response->getEmail());
            if ($user) {
                throw new AccountNotLinkedException("Email already exists.");
            }
        }
        if (null === $user) {
            $service = $response->getResourceOwner()->getName();

            $setter = 'set' . ucfirst($service);
            $setter_id = $setter . 'Id';
            $setter_token = $setter . 'AccessToken';

            //create new user
            $user = $this->userManager->createUser();
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());

            $user->setUsername($username);
            $user->setEmail($response->getEmail());
            $user->setPassword($username);
            $user->setEnabled(true);
            $user->setFirstname($response->getResponse()["first_name"]);
            $user->setLastname($response->getResponse()["last_name"]);
            $user->setFirstTime(false);
            $user->addRole("ROLE_FACEBOOK");
            $user->addRole("ROLE_CUSTOMER");

            $about = new UserInfo();
            $about->setGender(strtolower(($response->getResponse()["gender"]) == "male" ? 0 : 1));

            $responseData = $response->getResponse();
            if (isset($responseData['birthday'])) {
                $about->setBirthday(new \DateTime($responseData["birthday"]));
            }
            $about->setFacebook($response->getResponse()["link"]);
            $user->setUserInfo($about);

            $initializer = new PhotoInitializer($this->kernel->getRootDir()."/../src/Zgh/FEBundle/Resources/public/img/init", "init_pp.jpg", "init_cp.jpg");


            $pp = new UserPP();
            $cp = $initializer->initCoverPhoto();

//            $user->setProfilePhoto($pp);
            $user->setCoverPhoto($cp);

            $this->userManager->updateUser($user);
            return $user;
        }

        //if user with the specified username is in DB
        $user = parent::loadUserByOAuthUserResponse($response);
        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';

        //update access token
        $user->$setter($response->getAccessToken());

        return $user;
    }

}