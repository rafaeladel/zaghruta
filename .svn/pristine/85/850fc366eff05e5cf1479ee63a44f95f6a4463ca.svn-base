<?php
namespace Zgh\FEBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Kernel;
use Zgh\FEBundle\Entity\UserCP;
use Zgh\FEBundle\Entity\UserInfo;
use Zgh\FEBundle\Entity\UserPP;
use Zgh\FEBundle\Entity\VendorInfo;
use Zgh\FEBundle\Utility\PhotoInitializer;

class RegisterListener implements EventSubscriberInterface
{
    protected $manager;
    protected $kernel;
    public function __construct(UserManagerInterface $managerInterface, Kernel $kernel)
    {
        $this->manager = $managerInterface;
        $this->kernel = $kernel;
    }

    public static function getSubscribedEvents()
    {
        return(array(
           FOSUserEvents::REGISTRATION_INITIALIZE => "onRegistrationInitialize",
           FOSUserEvents::REGISTRATION_COMPLETED => "onRegistrationCompleted"
        ));
    }

    public function onRegistrationInitialize(GetResponseUserEvent $event)
    {
        $type = $event->getRequest()->query->get("t");
        $user = $event->getUser();
        if($type == "vendor"){
            $user->addRole("ROLE_VENDOR");
        }elseif($type == "customer"){
            $user->addRole("ROLE_CUSTOMER");
        } else {
            throw new NotFoundHttpException;
        }
    }

    public function onRegistrationCompleted(FilterUserResponseEvent $event)
    {

        $user = $event->getUser();
        $roles = $user->getRoles();

        if(in_array("ROLE_CUSTOMER", $roles))
        {
            $info = new UserInfo();
            $info->setStatus("Single");
            $user->setUserInfo($info);
        }
        elseif(in_array("ROLE_VENDOR", $roles))
        {
            $info = new VendorInfo();
            $user->setVendorInfo($info);
        }

        $initializer = new PhotoInitializer($this->kernel->getRootDir()."/../src/Zgh/FEBundle/Resources/public/img/init", "init_pp.jpg", "init_cp.jpg");

        $pp = $initializer->initProfilePhoto();
        $cp = $initializer->initCoverPhoto();
        $user->setProfilePhoto($pp);
        $user->setCoverPhoto($cp);

        $this->manager->updateUser($user);
    }



}