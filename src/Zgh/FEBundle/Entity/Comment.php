<?php

namespace Zgh\FEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;
use Zgh\FEBundle\Model\CommentableInterface;

/**
 * @ORM\Entity(repositoryClass="Zgh\FEBundle\Repository\CommentRepository")
 * @ORM\Table(name="comments")
 * @ORM\HasLifecycleCallbacks
 */
class Comment
{
    use BasicInfo;

    const COMMENT_TYPE_POST = 0;
    const COMMENT_TYPE_PHOTO = 1;
    const COMMENT_TYPE_EXPERIENCE = 2;
    const COMMENT_TYPE_TIP = 3;
    const COMMENT_TYPE_PRODUCT = 4;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $content;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="comments")
     */
    protected $user;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_removed;

    /**
     * @ORM\Column(type="text")
     */
    protected $object_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $object_type;

    protected $object;

    /**
     * @ORM\OneToOne(targetEntity="Notification", inversedBy="comment", cascade={"remove"})
     */
    protected $notification;


    /**
     * Set object_id
     *
     * @param integer $objectId
     * @return $this
     */
    public function setObjectId($objectId)
    {
        $this->object_id = $objectId;

        return $this;
    }

    /**
     * Get object_id
     *
     * @return integer
     */
    public function getObjectId()
    {
        return $this->object_id;
    }

    /**
     * Set object_type
     *
     * @param string $objectType
     * @return $this
     */
    public function setObjectType($objectType)
    {
        $this->object_type = $objectType;

        return $this;
    }

    /**
     * Get object_type
     *
     * @return string
     */
    public function getObjectType()
    {
        return $this->object_type;
    }

    public function setObject(CommentableInterface $obj)
    {
        $this->object = $obj;
        $this->object_id = $obj->getObjectId();
        $this->object_type = $obj->getObjectType();
    }

    /**
     * @var CommentableInterface::;
     */
    public function getObject()
    {
        return $this->object;
    }


    public static function getTypes()
    {
        return [
            self::COMMENT_TYPE_POST => 'Zgh\FEBundle\Entity\Post',
            self::COMMENT_TYPE_PHOTO => 'Zgh\FEBundle\Entity\Photo',
            self::COMMENT_TYPE_EXPERIENCE => 'Zgh\FEBundle\Entity\Experience',
            self::COMMENT_TYPE_TIP => 'Zgh\FEBundle\Entity\Tip',
            self::COMMENT_TYPE_PRODUCT => 'Zgh\FEBundle\Entity\Product'
        ];
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Comment
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
     * @return Comment
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
     * Set is_removed
     *
     * @param boolean $isRemoved
     * @return Comment
     */
    public function setIsRemoved($isRemoved)
    {
        $this->is_removed = $isRemoved;

        return $this;
    }

    /**
     * Get is_removed
     *
     * @return boolean 
     */
    public function getIsRemoved()
    {
        return $this->is_removed;
    }

    /**
     * @ORM\PrePersist
     */
    public function setIsRemovedValue()
    {
        $this->setIsRemoved(false);
    }

    /**
     * Set notification
     *
     * @param \Zgh\FEBundle\Entity\Notification $notification
     * @return Comment
     */
    public function setNotification(\Zgh\FEBundle\Entity\Notification $notification = null)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * Get notification
     *
     * @return \Zgh\FEBundle\Entity\Notification 
     */
    public function getNotification()
    {
        return $this->notification;
    }
}
