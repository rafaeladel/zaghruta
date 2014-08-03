<?php
namespace Zgh\FEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;
use Zgh\FEBundle\Model\Utilities\Image;

/**
 * @ORM\Entity
 * @ORM\Table(name="wishlists_images")
 * @ORM\HasLifecycleCallbacks
 */
class WishlistPhoto extends Image
{
    use BasicInfo;

    protected $cat_dir = "wishlists";
    protected $thumb_dir = "thumb";

    protected $thumb_h = 147;
    protected $thumb_w = 200;

    /**
     * @ORM\OneToOne(targetEntity="Zgh\FEBundle\Entity\Wishlist", inversedBy="image")
     */
    protected $wishlist;

    /**
     * Set wishlist
     *
     * @param \Zgh\FEBundle\Entity\Wishlist $wishlist
     * @return WishlistPhoto
     */
    public function setWishlist(\Zgh\FEBundle\Entity\Wishlist $wishlist = null)
    {
        $this->wishlist = $wishlist;

        return $this;
    }

    /**
     * Get wishlist
     *
     * @return \Zgh\FEBundle\Entity\Wishlist 
     */
    public function getWishlist()
    {
        return $this->wishlist;
    }
}
