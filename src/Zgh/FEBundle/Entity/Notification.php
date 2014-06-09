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
}
