<?php

namespace Zgh\FEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;

/**
 * @ORM\Entity
 * @ORM\Table(name="confs")
 */
class Conf
{
    use BasicInfo;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="user_confs_one")
     */
    protected $user_one;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="user_confs_two")
     */
    protected $user_two;

    /**
     * @ORM\OneToMany(targetEntity="ConfReply", mappedBy="conf")
     */
    protected $conf_replies;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->conf_replies = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set user_one
     *
     * @param \Zgh\FEBundle\Entity\User $userOne
     * @return Conf
     */
    public function setUserOne(\Zgh\FEBundle\Entity\User $userOne = null)
    {
        $this->user_one = $userOne;

        return $this;
    }

    /**
     * Get user_one
     *
     * @return \Zgh\FEBundle\Entity\User 
     */
    public function getUserOne()
    {
        return $this->user_one;
    }

    /**
     * Set user_two
     *
     * @param \Zgh\FEBundle\Entity\User $userTwo
     * @return Conf
     */
    public function setUserTwo(\Zgh\FEBundle\Entity\User $userTwo = null)
    {
        $this->user_two = $userTwo;

        return $this;
    }

    /**
     * Get user_two
     *
     * @return \Zgh\FEBundle\Entity\User 
     */
    public function getUserTwo()
    {
        return $this->user_two;
    }

    /**
     * Add conf_replies
     *
     * @param \Zgh\FEBundle\Entity\ConfReply $confReplies
     * @return Conf
     */
    public function addConfReply(\Zgh\FEBundle\Entity\ConfReply $confReplies)
    {
        $this->conf_replies[] = $confReplies;

        return $this;
    }

    /**
     * Remove conf_replies
     *
     * @param \Zgh\FEBundle\Entity\ConfReply $confReplies
     */
    public function removeConfReply(\Zgh\FEBundle\Entity\ConfReply $confReplies)
    {
        $this->conf_replies->removeElement($confReplies);
    }

    /**
     * Get conf_replies
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getConfReplies()
    {
        return $this->conf_replies;
    }
}
