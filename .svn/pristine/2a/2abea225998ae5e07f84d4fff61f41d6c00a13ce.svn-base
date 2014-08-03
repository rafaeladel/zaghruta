<?php

namespace Zgh\FEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_infos")
 * @ORM\HasLifecycleCallbacks
 */
class UserInfo
{
    use BasicInfo;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\NotBlank(groups={"intro", "Default"})
     */
    protected $birthday;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Assert\NotBlank(groups={"intro", "Default"})
     */
    protected $gender;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank
     */
    protected $status;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $city;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $job;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Regex(
     *      pattern = "/(?:(?:http|https):\/\/)?(?:www.)?facebook.com\/(?:(?:\w)*#!\/)?(?:pages\/)?(?:[?\w\-]*\/)?(?:profile.php\?id=(?=\d.*))?([\w\-]*)?/",
     *      message = "Invalid Facebook link"
     * )
     */
    protected $facebook;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Regex(
     *      pattern = "/https?:\/\/twitter\.com\/(#!\/)?[a-zA-Z0-9_]+/",
     *      message = "Invalid Twitter link"
     * )
     */
    protected $twitter;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="user_info")
     */
    protected $user;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="relationship_with_table")
     */
    protected $relationship_user;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $relationship_accepted;

    /**
     * Set twitter
     *
     * @param string $twitter
     * @return About
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * Get twitter
     *
     * @return string
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set birthday
     *
     * @param string $birthday
     * @return About
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return string
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return About
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return About
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return About
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set job
     *
     * @param string $job
     * @return About
     */
    public function setJob($job)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Get job
     *
     * @return string
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Set user
     *
     * @param \Zgh\FEBundle\Entity\User $user
     * @return About
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
     * Set facebook
     *
     * @param string $facebook
     * @return UserInfo
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook
     *
     * @return string 
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set relationship_user
     *
     * @param \Zgh\FEBundle\Entity\User $relationshipUser
     * @return UserInfo
     */
    public function setRelationshipUser(\Zgh\FEBundle\Entity\User $relationshipUser = null)
    {
        $this->relationship_user = $relationshipUser;

        return $this;
    }

    /**
     * Get relationship_user
     *
     * @return \Zgh\FEBundle\Entity\User 
     */
    public function getRelationshipUser()
    {
        return $this->relationship_user;
    }


    /**
     * Set relationship_accepted
     *
     * @param boolean $relationshipAccepted
     * @return UserInfo
     */
    public function setRelationshipAccepted($relationshipAccepted)
    {
        $this->relationship_accepted = $relationshipAccepted;

        return $this;
    }

    /**
     * Get relationship_accepted
     *
     * @return boolean 
     */
    public function getRelationshipAccepted()
    {
        return $this->relationship_accepted;
    }

    /**
     * @ORM\PrePersist
     */
    public function setRelationshipAcceptedValue()
    {
        $this->setRelationshipAccepted(false);
    }
}
