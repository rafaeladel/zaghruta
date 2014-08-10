<?php

namespace Zgh\FEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;
use Zgh\FEBundle\Model\LikeableInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="likes")
 * @ORM\HasLifecycleCallbacks
 */
class Like
{
    use BasicInfo;

    const LIKE_TYPE_POST = 0;
    const LIKE_TYPE_PHOTO = 1;
    const LIKE_TYPE_EXPERIENCE = 2;
    const LIKE_TYPE_TIP = 3;
    const LIKE_TYPE_PRODUCT = 4;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="likes")
     */
    protected $user;

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
     * @ORM\OneToOne(targetEntity="Notification", inversedBy="like", cascade={"remove"})
     */
    protected $notification;

    /**
     * Set user
     *
     * @param \Zgh\FEBundle\Entity\User $user
     * @return Like
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
     * Set object_id
     *
     * @param integer $objectId
     * @return Like
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
     * @return Like
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

    public function setObject(LikeableInterface $obj)
    {
        $this->object = $obj;
        $this->object_id = $obj->getObjectId();
        $this->object_type = $obj->getObjectType();
    }

    /**
     * @var LikeableInterface;
     */
    public function getObject()
    {
        return $this->object;
    }


    public static function getTypes()
    {
        return [
            self::LIKE_TYPE_POST => 'Zgh\FEBundle\Entity\Post',
            self::LIKE_TYPE_PHOTO => 'Zgh\FEBundle\Entity\Photo',
            self::LIKE_TYPE_EXPERIENCE => 'Zgh\FEBundle\Entity\Experience',
            self::LIKE_TYPE_TIP => 'Zgh\FEBundle\Entity\Tip',
            self::LIKE_TYPE_PRODUCT => 'Zgh\FEBundle\Entity\Product'
        ];
    }

    /**
     * Set notification
     *
     * @param \Zgh\FEBundle\Entity\Notification $notification
     * @return Like
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
