<?php

namespace Zgh\MsgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;

/**
 * DeletedMessage
 *
 * @ORM\Table("deleted_messages")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class DeletedMessage
{
    use BasicInfo;

    /**
     * @ORM\ManyToOne(targetEntity="Zgh\MsgBundle\Entity\Message", inversedBy="delete_table")
     */
    protected $message;

    /**
     * @ORM\ManyToOne(targetEntity="Zgh\FEBundle\Entity\User", inversedBy="delete_table")
     */
    protected $user;


    /**
     * Set message
     *
     * @param \Zgh\MsgBundle\Entity\Message $message
     * @return DeletedMessage
     */
    public function setMessage(\Zgh\MsgBundle\Entity\Message $message = null)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return \Zgh\MsgBundle\Entity\Message 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set user
     *
     * @param \Zgh\FEBundle\Entity\User $user
     * @return DeletedMessage
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
}
