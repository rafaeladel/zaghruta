<?php

namespace Zgh\FEBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\CommentableInterface;
use Zgh\FEBundle\Model\LikeableInterface;
use Zgh\FEBundle\Model\Partial\BasicInfo;
use Zgh\FEBundle\Model\Utilities\Image;

/**
 * @ORM\Entity
 * @ORM\Table(name="products")
 * @ORM\HasLifecycleCallbacks
 */
class Product extends Image implements LikeableInterface, CommentableInterface
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
     * @ORM\ManyToMany(targetEntity="Wishlist", inversedBy="products")
     * @ORM\JoinTable(name="wishlist_products",
     *      joinColumns={@ORM\JoinColumn(name="wishlist_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")}
     *      )
     */
    protected $wishlists;

    /**
     * @var ArrayCollection
     */
    protected $likes;

    /**
     * @var ArrayCollection
     */
    protected $comments;

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
        $wishlists->addProduct($this);
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

    public function getObjectId()
    {
        return $this->getId();
    }

    public function getObjectType()
    {
        return Like::LIKE_TYPE_PRODUCT;
    }

    public function addComment(Comment $comment)
    {
        if(!$comment->getIsRemoved()){
            $this->comments[] = $comment;
        }
        return $this;
    }

    public function setComments(ArrayCollection $comments)
    {
        $approved_arr = new ArrayCollection();
        foreach($comments as $comment)
        {
            if(!$comment->getIsRemoved()) {
                $approved_arr[] = $comment;
            }
        }
        $this->comments = $approved_arr;
        return $this;
    }

    public function removeComment(\Zgh\FEBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
        return $this;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function addLike(Like $like)
    {
        $this->likes[] = $like;
        return $this;
    }

    public function setLikes(ArrayCollection $likes)
    {
        $this->likes = $likes;
        return $this;
    }

    public function removeLike(\Zgh\FEBundle\Entity\Like $likes)
    {
        $this->likes->removeElement($likes);
        return $this;
    }

    public function getLikes()
    {
        return $this->likes;
    }
}
