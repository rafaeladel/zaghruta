<?php

namespace Zgh\FEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="vendor_infos")
 * @ORM\HasLifecycleCallbacks
 */
class VendorInfo
{
    use BasicInfo;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    protected $company_name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $info;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $mobile;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Email
     */
    protected $email;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Regex(
     *      pattern = "/http:\/\/twitter\.com\/(#!\/)?[a-zA-Z0-9_]+/",
     *      message = "Invalid Twitter link"
     * )
     */
    protected $twitter;

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
     * @Assert\Url
     */
    protected $website;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="vendor_info")
     */
    protected $user;


    /**
     * Set info
     *
     * @param string $info
     * @return VendorInfo
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info
     *
     * @return string 
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     * @return VendorInfo
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
     * @return VendorInfo
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
     * Set twitter
     *
     * @param string $twitter
     * @return VendorInfo
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
     * Set facebook
     *
     * @param string $facebook
     * @return VendorInfo
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
     * Set website
     *
     * @param string $website
     * @return VendorInfo
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }


    /**
     * Set user
     *
     * @param \Zgh\FEBundle\Entity\User $user
     * @return VendorInfo
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
     * Set company_name
     *
     * @param string $companyName
     * @return VendorInfo
     */
    public function setCompanyName($companyName)
    {
        $this->company_name = $companyName;

        return $this;
    }

    /**
     * Get company_name
     *
     * @return string 
     */
    public function getCompanyName()
    {
        return $this->company_name;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCompanyNameValue()
    {
        $this->setCompanyName("Company Name");
    }
}
