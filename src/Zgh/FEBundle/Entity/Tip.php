<?php
namespace Zgh\FEBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Zgh\FEBundle\Model\CommentableInterface;
use Zgh\FEBundle\Model\LikeableInterface;
use Zgh\FEBundle\Model\Partial\BasicInfo;
use Zgh\FEBundle\Model\Utilities\Image;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="tips")
 * @ORM\HasLifecycleCallbacks
 */
class Tip extends Image implements LikeableInterface, CommentableInterface
{
    use BasicInfo;

    protected $cat_dir = "tips";
    protected $thumb_dir = "thumb";

    protected $thumb_h = 265;
    protected $thumb_w = 682;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tips")
     */
    protected $user;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    protected $title;


    /**
     * @ORM\ManyToMany(targetEntity="Zgh\FEBundle\Entity\Category", inversedBy="tips")
     * @ORM\JoinTable(name="tip_category")
     * @Assert\NotBlank()
     */
    protected $categories;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    protected $content;

    /**
     * @var ArrayCollection
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
     * @return Tip
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
     * @return Tip
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
     * @return Tip
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

    public function getObjectId()
    {
        return $this->getId();
    }

    public function getObjectType()
    {
        return Like::LIKE_TYPE_TIP;
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add categories
     *
     * @param \Zgh\FEBundle\Entity\Category $categories
     * @return Tip
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
