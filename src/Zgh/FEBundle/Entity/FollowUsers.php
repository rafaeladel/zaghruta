<?php

namespace Zgh\FEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;

/**
 * @ORM\Entity(repositoryClass="Zgh\FEBundle\Repository\FollowRepository")
 * @ORM\Table(name="follow_users")
 * @ORM\HasLifecycleCallbacks
 */
class FollowUsers
{
    use BasicInfo;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="followers")
     */
    protected $follower;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="followees")
     */
    protected $followee;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_approved;

    /**
     * Set follower
     *
     * @param \Zgh\FEBundle\Entity\User $follower
     * @return FollowUsers
     */
    public function setFollower(\Zgh\FEBundle\Entity\User $follower = null)
    {
        $this->follower = $follower;

        return $this;
    }

    /**
     * Get follower
     *
     * @return \Zgh\FEBundle\Entity\User 
     */
    public function getFollower()
    {
        return $this->follower;
    }

    /**
     * Set followee
     *
     * @param \Zgh\FEBundle\Entity\User $followee
     * @return FollowUsers
     */
    public function setFollowee(\Zgh\FEBundle\Entity\User $followee = null)
    {
        $this->followee = $followee;

        return $this;
    }

    /**
     * Get followee
     *
     * @return \Zgh\FEBundle\Entity\User 
     */
    public function getFollowee()
    {
        return $this->followee;
    }

    /**
     * Set is_approved
     *
     * @param boolean $isApproved
     * @return FollowUsers
     */
    public function setIsApproved($isApproved)
    {
        $this->is_approved = $isApproved;

        return $this;
    }

    /**
     * Get is_approved
     *
     * @return boolean 
     */
    public function getIsApproved()
    {
        return $this->is_approved;
    }
}
