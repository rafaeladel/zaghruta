<?php
namespace Zgh\FEBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\ExecutionContextInterface;
use Zgh\FEBundle\Model\Partial\BasicInfo;
use Zgh\FEBundle\Model\CommentableInterface;
use Zgh\FEBundle\Model\LikeableInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Zgh\FEBundle\Model\Utilities\Image;

/**
 * @ORM\Entity(repositoryClass="Zgh\FEBundle\Repository\PostRepository")
 * @ORM\Table(name="posts")
 * @ORM\HasLifecycleCallbacks
 */
class Post extends Image implements LikeableInterface, CommentableInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    protected $cat_dir = "posts";
    protected $thumb_dir = "thumb";

//    protected $thumb_h = 100;
    protected $thumb_w = 600;


    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="posts")
     */
    protected $user;

    /**
     * @var ArrayCollection
     */
    protected $comments;

    /**
     * @var ArrayCollection
     */
    protected $likes;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $video;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $content;

    /**
     * @Assert\Callback
     */
    public function validateContent(ExecutionContextInterface $context)
    {
        if(empty($this->content)){

            if(empty($this->image_file))
            {
                $context->buildViolation("You have to select a photo or enter content of the post or both.")
                        ->addViolation();
            }
        }
    }

    /**
     * Set user
     *
     * @param \Zgh\FEBundle\Entity\User $user
     * @return Post
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
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->likes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Post
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
     * Set video
     *
     * @param string $video
     * @return Post
     */
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return string 
     */
    public function getVideo()
    {
        return $this->video;
    }

    public function getObjectId()
    {
        return $this->getId();
    }

    public function getObjectType()
    {
        return Like::LIKE_TYPE_POST;
    }

    /**
     * @param Like $like
     * @return $this
     */
    public function addLike(Like $like)
    {
        $this->likes[] = $like;
        return $this;
    }

    /**
     * @param ArrayCollection $likes
     * @return $this
     */
    public function setLikes(ArrayCollection $likes)
    {
        $this->likes = $likes;
        return $this;
    }

    /**
     * @param Like $like
     * @return $this
     */
    public function removeLike(\Zgh\FEBundle\Entity\Like $like)
    {
        $this->likes->removeElement($like);
        return $this;
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
     * @param Comment $comment
     * @return $this
     */
    public function addComment(Comment $comment)
    {
        if(!$comment->getIsRemoved()){
            $this->comments[] = $comment;
        }
        return $this;
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
     * @param Comment $comment
     * @return $this
     */
    public function removeComment(\Zgh\FEBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    public function hasImage()
    {
        return !empty($this->image_name);
    }

    /**
     * @ORM\PrePersist
     */
    public function setId()
    {
        $this->id = uniqid();
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->setCreatedAt(new \DateTime());
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->setUpdatedAt(new \DateTime());
    }

    public function getId()
    {
        return $this->id;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}
