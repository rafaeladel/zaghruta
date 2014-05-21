<?php

namespace Zgh\FEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;

/**
 * @ORM\Entity
 * @ORM\Table(name="products")
 */
class Product
{
    use BasicInfo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $price;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="products")
     */
    protected $user;

    /**
     * @ORM\ManyToMany(targetEntity="Wishlist")
     */
    protected $wishlists;
//
//    /**
//     * @ORM\OneToOne(targetEntity="Zgh\FEBundle\Entity\Utilities\ImageContainer", cascade={"persist", "remove"})
//     */
//    protected $image_container;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->wishlists = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set user
     *
     * @param \Zgh\FEBundle\Entity\User $user
     * @return Product
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

    /**
     * Add wishlists
     *
     * @param \Zgh\FEBundle\Entity\Wishlist $wishlists
     * @return Product
     */
    public function addWishlist(\Zgh\FEBundle\Entity\Wishlist $wishlists)
    {
        $this->wishlists[] = $wishlists;

        return $this;
    }

    /**
     * Remove wishlists
     *
     * @param \Zgh\FEBundle\Entity\Wishlist $wishlists
     */
    public function removeWishlist(\Zgh\FEBundle\Entity\Wishlist $wishlists)
    {
        $this->wishlists->removeElement($wishlists);
    }

    /**
     * Get wishlists
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWishlists()
    {
        return $this->wishlists;
    }

    /**
     * Set image_container
     *
     * @param \Zgh\FEBundle\Entity\Utilities\ImageContainer $imageContainer
     * @return Product
     */
    public function setImageContainer(\Zgh\FEBundle\Entity\Utilities\ImageContainer $imageContainer = null)
    {
        $this->image_container = $imageContainer;

        return $this;
    }

    /**
     * Get image_container
     *
     * @return \Zgh\FEBundle\Entity\Utilities\ImageContainer 
     */
    public function getImageContainer()
    {
        return $this->image_container;
    }
}
