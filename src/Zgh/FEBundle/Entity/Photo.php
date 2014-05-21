<?php

namespace Zgh\FEBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;
use Zgh\FEBundle\Model\Utilities\Image;
use Zgh\FEBundle\Model\CommentableInterface;
use Zgh\FEBundle\Model\LikeableInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="photos")
 * @ORM\HasLifecycleCallbacks
 */
class Photo extends Image implements LikeableInterface, CommentableInterface
{
    use BasicInfo;


    protected $cat_dir = "photos";
    protected $thumb_dir = "thumb";

    protected $thumb_h = 183;
    protected $thumb_w = 208;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="photos")
     */
    protected $user;


    /**
     * @ORM\ManyToOne(targetEntity="Album", inversedBy="photos")
     */
    protected $album;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $caption;

    /**
     * @var ArrayCollection
     */
    protected $comments;

    /**
     * @var ArrayCollection
     */
    protected $likes;


    /**
     * Set description
     *
     * @param string $description
     * @return Photo
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
     * Set album
     *
     * @param \Zgh\FEBundle\Entity\Album $album
     * @return Photo
     */
    public function setAlbum(\Zgh\FEBundle\Entity\Album $album = null)
    {
        $this->album = $album;

        return $this;
    }

    /**
     * Get album
     *
     * @return \Zgh\FEBundle\Entity\Album 
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * Set user
     *
     * @param \Zgh\FEBundle\Entity\User $user
     * @return Photo
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
     * Set caption
     *
     * @param string $caption
     * @return Photo
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * Get caption
     *
     * @return string 
     */
    public function getCaption()
    {
        return $this->caption;
    }


    public function addLike(Like $like)
    {
        $this->likes[] = $like;
    }

    public function setLikes(ArrayCollection $likes)
    {
        $this->likes = $likes;
    }

    /**
     * Remove likes
     *
     * @param \Zgh\FEBundle\Entity\Like $likes
     */
    public function removeLike(\Zgh\FEBundle\Entity\Like $likes)
    {
        $this->likes->removeElement($likes);
    }

    /**
     * Get likes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLikes()
    {
        return $this->likes;
    }

    public function getObjectId()
    {
        return $this->getId();
    }

    public function getObjectType()
    {
        return Like::LIKE_TYPE_PHOTO;
    }

    /**
     * Add comments
     *
     * @param \Zgh\FEBundle\Entity\Comment $comments
     * @return Photo
     */
    public function addComment(\Zgh\FEBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;
        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Zgh\FEBundle\Entity\Comment $comment
     */
    public function removeComment(\Zgh\FEBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }


    /**
     * @param ArrayCollection $comments
     * @return $this
     */
    public function setComments(ArrayCollection $comments)
    {
        $this->comments = $comments;
        return $this;
    }
}
