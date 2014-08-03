<?php
namespace Zgh\FEBundle\TwigExtension;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Twig_Environment;
use Zgh\FEBundle\Entity\User;

class RenderFollowExtension extends \Twig_Extension
{
    protected $em;

    /**
     * @var SecurityContextInterface
     */
    protected $security_context;

    /**
     * @var FollowCheckExtension
     */
    protected $follow_check;

    protected $router;
    protected $status;

    /**
     * @var Twig_Environment
     */
    protected $env;

    public function __construct(
        EntityManagerInterface $entityManagerInterface,
        SecurityContextInterface $context,
        FollowCheckExtension $follow_check,
        RouterInterface $router
    ){
        $this->em = $entityManagerInterface;
        $this->security_context = $context;
        $this->follow_check = $follow_check;
        $this->router = $router;
    }

    public function initRuntime(Twig_Environment $environment)
    {
        $this->env = $environment;
    }


    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction("renderFollow", array($this, "renderFollow"), array('is_safe' => array('html'))),
        );
    }

    /**
     * @param User $user
     * @param string $classes
     * @return null|string
     */
    public function renderFollow(User $user, $classes = "")
    {
        $current_user = $this->security_context->getToken()->getUser();

        if(!$current_user instanceof User)
        {
            return null;
        }

        //User viewing his own profile. No follow button needed
        if($user->getId() == $current_user->getId()){
            return null;
        }

        $path = $this->router->generate("zgh_fe.user.set_follow", array(
                "er_id"     => $current_user->getId(),
                "ing_id"    => $user->getId()
             ));

        $status = $this->follow_check->checkFollow($current_user, $user);

        $txt = '';
        if($status != null)
        {
            if($status->getIsApproved() == true)
            {
                $txt = "Unfollow";
            }
            else
            {
                $txt = "Pending";
            }
        }
        elseif($status == null)
        {
            $txt = "Follow";
        }

        return $this->env->render("@ZghFE/Partial/follow/follow_btn.html.twig", [
                "path" => $path,
                "msg" => $txt,
                "classes" => $classes
            ]);

    }

    public function getName()
    {
        return "zgh_extension_render_follow";
    }
}