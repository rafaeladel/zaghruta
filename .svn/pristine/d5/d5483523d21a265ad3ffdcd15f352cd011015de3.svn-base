<?php
namespace Zgh\FEBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;
use Zgh\FEBundle\Model\CommentableInterface;
use Zgh\FEBundle\Model\LikeableInterface;

/**
 * @ORM\Entity(repositoryClass="Zgh\FEBundle\Repository\PostRepository")
 * @ORM\Table(name="posts")
 * @ORM\HasLifecycleCallbacks
 */
class Post implements LikeableInterface, CommentableInterface
{
    use BasicInfo;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="posts")
     */
    protected $user;

    /**
     * @var ArrayCollection::
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
     * @ORM\Column(type="text")
     */
    protected $content;

    /**
     * @ORM\OneToOne(targetEntity="Zgh\FEBundle\Entity\PostImage", cascade={"persist", "remove"}, mappedBy="post")
     */
    protected $image;

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
     * Set image
     *
     * @param \Zgh\FEBundle\Entity\PostImage $image
     * @return Post
     */
    public function setImage(\Zgh\FEBundle\Entity\PostImage $image = null)
    {
        $this->image = $image;
        $image->setPost($this);
        return $this;
    }

    /**
     * Get image
     *
     * @return \Zgh\FEBundle\Entity\PostImage 
     */
    public function getImage()
    {
        return $this->image;
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
}
