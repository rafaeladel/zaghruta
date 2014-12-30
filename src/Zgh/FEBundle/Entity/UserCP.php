<?php
namespace Zgh\FEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;
use Zgh\FEBundle\Model\Utilities\Image;

/**
 * @ORM\Entity
 * @ORM\Table(name="users_cover_photos")
 * @ORM\HasLifecycleCallbacks
 */
class UserCP extends Image
{
    use BasicInfo;

    protected $cat_dir = "user_cp";
    protected $thumb_dir = "thumb";

    protected $thumb_h = 175;
    protected $thumb_w = 950;

    /**
     * @ORM\OneToOne(targetEntity="Zgh\FEBundle\Entity\User", inversedBy="cover_photo")
     */
    protected $user;

    /**
     * Set user
     *
     * @param \Zgh\FEBundle\Entity\User $user
     * @return UserCP
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
