<?php

namespace Zgh\FEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;

/**
 * @ORM\Entity
 * @ORM\Table(name="notifications_reads")
 */
class NotificationRead
{
    use BasicInfo;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="n_read")
     */
    protected $user;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $last_time;


    /**
     * Set last_time
     *
     * @param \DateTime $lastTime
     * @return NotificationRead
     */
    public function setLastTime($lastTime)
    {
        $this->last_time = $lastTime;

        return $this;
    }

    /**
     * Get last_time
     *
     * @return \DateTime 
     */
    public function getLastTime()
    {
        return $this->last_time;
    }

    /**
     * Set user
     *
     * @param \Zgh\FEBundle\Entity\User $user
     * @return NotificationRead
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
