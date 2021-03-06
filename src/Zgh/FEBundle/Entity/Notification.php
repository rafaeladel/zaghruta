<?php

namespace Zgh\FEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;

/**
 * @ORM\Entity(repositoryClass="Zgh\FEBundle\Repository\NotificationRepository")
 * @ORM\Table(name="notifications")
 * @ORM\HasLifecycleCallbacks
 */
class Notification
{
    use BasicInfo;

    const TYPE_lIKE = 0;
    const TYPE_COMMENT = 1;
    const TYPE_FOLLOW = 2;
    const TYPE_FOLLOW_REQUEST = 3;
    const TYPE_RELATIONSHIP_REQUEST = 4;
    const TYPE_MESSAGE = 5;



    /**
     * @ORM\Column(type="json_array")
     */
    protected $content;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_read;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="notifications")
     */
    protected $user;

    /**
     * @ORM\Column(type="text")
     */
    protected $action_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $type;

    /**
     * @ORM\OneToOne(targetEntity="Like", mappedBy="notification")
     */
    protected $like;

    /**
     * @ORM\OneToOne(targetEntity="Comment", mappedBy="notification")
     */
    protected $comment;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $other_end;

    /**
     * Set content
     *
     * @param array $content
     * @return Notification
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return array 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set is_read
     *
     * @param boolean $isRead
     * @return Notification
     */
    public function setIsRead($isRead)
    {
        $this->is_read = $isRead;

        return $this;
    }

    /**
     * Get is_read
     *
     * @return boolean 
     */
    public function getIsRead()
    {
        return $this->is_read;
    }

    /**
     * Set user
     *
     * @param \Zgh\FEBundle\Entity\User $user
     * @return Notification
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
     * @ORM\PrePersist
     */
    public function setIsReadValue()
    {
        $this->setIsRead(false);
    }

    /**
     * Set action_id
     *
     * @param string $actionId
     * @return Notification
     */
    public function setActionId($actionId)
    {
        $this->action_id = $actionId;

        return $this;
    }

    /**
     * Get action_id
     *
     * @return string 
     */
    public function getActionId()
    {
        return $this->action_id;
    }

    /**
     * Set like
     *
     * @param \Zgh\FEBundle\Entity\Like $like
     * @return Notification
     */
    public function setLike(\Zgh\FEBundle\Entity\Like $like = null)
    {
        $this->like = $like;
        $like->setNotification($this);
        return $this;
    }

    /**
     * Get like
     *
     * @return \Zgh\FEBundle\Entity\Like 
     */
    public function getLike()
    {
        return $this->like;
    }

    /**
     * Set comment
     *
     * @param \Zgh\FEBundle\Entity\Comment $comment
     * @return Notification
     */
    public function setComment(\Zgh\FEBundle\Entity\Comment $comment = null)
    {
        $this->comment = $comment;
        $comment->setNotification($this);
        return $this;
    }

    /**
     * Get comment
     *
     * @return \Zgh\FEBundle\Entity\Comment 
     */
    public function getComment()
    {
        return $this->comment;
    }


    /**
     * Set other_end
     *
     * @param \Zgh\FEBundle\Entity\User $otherEnd
     * @return Notification
     */
    public function setOtherEnd(\Zgh\FEBundle\Entity\User $otherEnd = null)
    {
        $this->other_end = $otherEnd;

        return $this;
    }

    /**
     * Get other_end
     *
     * @return \Zgh\FEBundle\Entity\User 
     */
    public function getOtherEnd()
    {
        return $this->other_end;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Notification
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
}
