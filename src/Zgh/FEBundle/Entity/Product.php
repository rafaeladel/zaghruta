<?php

namespace Zgh\FEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;
use Zgh\FEBundle\Model\Utilities\Image;

/**
 * @ORM\Entity
 * @ORM\Table(name="products")
 * @ORM\HasLifecycleCallbacks
 */
class Product extends Image
{
    use BasicInfo;

    protected $cat_dir = "products";
    protected $thumb_dir = "thumb";

    protected $thumb_h = 265;
    protected $thumb_w = 682;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $name;

    /**
     * @ORM\ManyToMany(targetEntity="Category", cascade={"persist", "remove"})
     */
    protected $categories;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", cascade={"persist", "remove"})
     */
    protected $tags;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
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
     * Add categories
     *
     * @param \Zgh\FEBundle\Entity\Category $categories
     * @return Product
     */
    public function addCategory(\Zgh\FEBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;
        $categories->addProduct($this);
        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Zgh\FEBundle\Entity\Category $categories
     */
    public function removeCategory(\Zgh\FEBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add tags
     *
     * @param \Zgh\FEBundle\Entity\Tag $tags
     * @return Product
     */
    public function addTag(\Zgh\FEBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;
        $tags->addProduct($this);
        return $this;
    }

    /**
     * Remove tags
     *
     * @param \Zgh\FEBundle\Entity\Tag $tags
     */
    public function removeTag(\Zgh\FEBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }
}
