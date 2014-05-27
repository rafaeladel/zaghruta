<?php
namespace Zgh\FEBundle\TwigExtension;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Zgh\FEBundle\Entity\User;

class RenderFollowExtension extends \Twig_Extension
{
    protected $em;
    protected $security_context;
    protected $follow_check;
    protected $router;

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

        //User viewing his own profile. No follow button needed
        if($user->getId() == $current_user->getId()){
            return null;
        }

        $path = $this->router->generate("zgh_fe.user.set_follow", array(
                "er_id"     => $current_user->getId(),
                "ing_id"    => $user->getId()
             ));

        $markup = "<form action='".$path."' method='post'>
            <button class='btn btn-primary btn-wide  btnFollowing ".$classes."' type='submit'>".
                $this->currentFollowMarkup($current_user->getId(), $user->getId())
            ."</button>
        </form>";

        return $markup;

    }

    /**
     * Checks viewed profile status, Followed or Not followed
     *
     * @param $follower_id
     * @param $followee_id
     * @return string
     */
    public function currentFollowMarkup($follower_id, $followee_id)
    {
        $status = $this->follow_check->checkFollow($follower_id, $followee_id);

        $txt = '';
        if($status != null)
        {
            $txt = "Unfollow";
        }
        elseif($status == null)
        {
            $txt = "Follow";
        }

        $button = "
                    <span class='follow'>
                        <!--<span class='following'>Following</span>-->
                        <!--<span class='Unfollow'><span class='glyphicon glyphicon-minus-sign'></span>Unfollow</span>-->
                        <span class='following following_msg'>".$txt."</span>
                        <!--<span class='Unfollow'><span class='glyphicon glyphicon-minus-sign'></span>Unfollow</span>-->
                    </span>
                    ";

        return $button;
    }

    public function getName()
    {
        return "zgh_extension_render_follow";
    }
}