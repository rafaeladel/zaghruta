<?php

namespace Zgh\FEBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;
use Zgh\FEBundle\Model\Utilities\Image;
use Symfony\Component\Validator\Constraints as Assert;
use Zgh\FEBundle\Model\CommentableInterface;
use Zgh\FEBundle\Model\LikeableInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="experiences")
 * @ORM\HasLifecycleCallbacks
 */
class Experience extends Image implements LikeableInterface, CommentableInterface
{
    use BasicInfo;


    protected $cat_dir = "experiences";
    protected $thumb_dir = "thumb";

    protected $thumb_h = 265;
    protected $thumb_w = 682;


    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    protected $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    protected $content;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="experiences")
     */
    protected $user;

    /**
     * @ORM\ManyToMany(targetEntity="Zgh\FEBundle\Entity\Category", inversedBy="experiences")
     * @ORM\JoinTable(name="experience_category")
     * @Assert\NotBlank()
     */
    protected $categories;


    /**
     * @var ArrayCollection::
     */
    protected $comments;

    /**
     * @var ArrayCollection
     */
    protected $likes;

    /**
     * Set title
     *
     * @param string $title
     * @return Experience
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Experience
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set user
     *
     * @param \Zgh\FEBundle\Entity\User $user
     * @return Experience
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
     * Constructor
     */
    public function __construct()
    {
        $this->category = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Add comment
     *
     * @param \Zgh\FEBundle\Entity\Comment $comment
     * @return Experience
     */
    public function addComment(\Zgh\FEBundle\Entity\Comment $comment)
    {
        if(!$comment->getIsRemoved()){
            $this->comments[] = $comment;
        }
        return $this;
    }

    /**
     * Remove comment
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

    public function getObjectId()
    {
        return $this->getId();
    }

    public function getObjectType()
    {
        //TODO: How to put COMMENT::LIKE_TYPE_EXPERIENCE too ?!
        return Like::LIKE_TYPE_EXPERIENCE;
    }

    /**
     * @param ArrayCollection $comments
     * @return $this
     */
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

    /**
     * Add categories
     *
     * @param \Zgh\FEBundle\Entity\Category $categories
     * @return Experience
     */
    public function addCategory(\Zgh\FEBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

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

}
