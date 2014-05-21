<?php

namespace Zgh\FEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;

/**
 * @ORM\Entity
 * @ORM\Table(name="notifications")
 */
class Notification
{
    use BasicInfo;

    /**
     * @ORM\Column(type="text")
     */
    protected $n_type;

    /**
     * @ORM\Column(type="text")
     */
    protected $n_content;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $n_time;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $n_url;

    /**
     * @ORM\ManyToMany(targetEntity="User")
     */
    protected $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set n_type
     *
     * @param string $nType
     * @return Notification
     */
    public function setNType($nType)
    {
        $this->n_type = $nType;

        return $this;
    }

    /**
     * Get n_type
     *
     * @return string 
     */
    public function getNType()
    {
        return $this->n_type;
    }

    /**
     * Set n_content
     *
     * @param string $nContent
     * @return Notification
     */
    public function setNContent($nContent)
    {
        $this->n_content = $nContent;

        return $this;
    }

    /**
     * Get n_content
     *
     * @return string 
     */
    public function getNContent()
    {
        return $this->n_content;
    }

    /**
     * Set n_time
     *
     * @param \DateTime $nTime
     * @return Notification
     */
    public function setNTime($nTime)
    {
        $this->n_time = $nTime;

        return $this;
    }

    /**
     * Get n_time
     *
     * @return \DateTime 
     */
    public function getNTime()
    {
        return $this->n_time;
    }

    /**
     * Set n_url
     *
     * @param string $nUrl
     * @return Notification
     */
    public function setNUrl($nUrl)
    {
        $this->n_url = $nUrl;

        return $this;
    }

    /**
     * Get n_url
     *
     * @return string 
     */
    public function getNUrl()
    {
        return $this->n_url;
    }

    /**
     * Add users
     *
     * @param \Zgh\FEBundle\Entity\User $users
     * @return Notification
     */
    public function addUser(\Zgh\FEBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \Zgh\FEBundle\Entity\User $users
     */
    public function removeUser(\Zgh\FEBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
}
