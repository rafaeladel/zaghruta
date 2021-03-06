<?php

namespace Zgh\FEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="branches")
 * @ORM\HasLifecycleCallbacks
 */
class Branch
{
    use BasicInfo;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    protected $address;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $city;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Regex(pattern="/[+0-9]+/", message="Only Numbers Allowed")
     */
    protected $phone;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Regex(pattern="/[+0-9]+/", message="Only Numbers Allowed")
     */
    protected $mobile;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Email()
     */
    protected $email;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="branches")
     */
    protected $user;

    /**
     * Set address
     *
     * @param string $address
     * @return Branch
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Branch
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     * @return Branch
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string 
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Branch
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set user
     *
     * @param \Zgh\FEBundle\Entity\User $user
     * @return Branch
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
     * Set city
     *
     * @param string $city
     * @return Branch
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
}
