<?php
namespace Zgh\FEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;
use Zgh\FEBundle\Model\Utilities\Image;

/**
 * @ORM\Entity
 * @ORM\Table(name="users_profile_photos")
 * @ORM\HasLifecycleCallbacks
 */
class UserPP extends Image
{
    use BasicInfo;

    protected $cat_dir = "user_pp";
    protected $thumb_dir = "thumb";

    protected $thumb_h = 125;
    protected $thumb_w = 125;

    /**
     * @ORM\OneToOne(targetEntity="Zgh\FEBundle\Entity\User", inversedBy="profile_photo")
     */
    protected $user;

    /**
     * Set user
     *
     * @param \Zgh\FEBundle\Entity\User $user
     * @return UserPP
     */
    public function setUser(\Zgh\FEBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Zgh\FEBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
