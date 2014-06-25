<?php

namespace Zgh\FEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;

/**
 * @ORM\Entity
 * @ORM\Table(name="categories")
 * @ORM\HasLifecycleCallbacks
 */
class Category
{
    use BasicInfo;

    /**
     * @ORM\ManyToMany(targetEntity="Experience", mappedBy="categories")
     */
    protected $experiences;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
     */
    protected $products;

    /**
     * @ORM\ManyToMany(targetEntity="User")
     */
    protected $users;

    /**
     * @ORM\Column(type="text")
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $name_slug;

    /**
     * @ORM\Column(type="text")
     */
    protected $css_class;

    /**
     * @ORM\ManyToMany(targetEntity="VendorInfo", mappedBy="categories")
     */
    protected $vendors;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->experiences = new \Doctrine\Common\Collections\ArrayCollection();
    }



    /**
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add users
     *
     * @param \Zgh\FEBundle\Entity\User $users
     * @return Category
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

    /**
     * Add products
     *
     * @param \Zgh\FEBundle\Entity\Product $products
     * @return Category
     */
    public function addProduct(\Zgh\FEBundle\Entity\Product $products)
    {
        $this->products[] = $products;

        return $this;
    }

    /**
     * Remove products
     *
     * @param \Zgh\FEBundle\Entity\Product $products
     */
    public function removeProduct(\Zgh\FEBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Add vendors
     *
     * @param \Zgh\FEBundle\Entity\VendorInfo $vendors
     * @return Category
     */
    public function addVendor(\Zgh\FEBundle\Entity\VendorInfo $vendors)
    {
        $this->vendors[] = $vendors;

        return $this;
    }

    /**
     * Remove vendors
     *
     * @param \Zgh\FEBundle\Entity\VendorInfo $vendors
     */
    public function removeVendor(\Zgh\FEBundle\Entity\VendorInfo $vendors)
    {
        $this->vendors->removeElement($vendors);
    }

    /**
     * Get vendors
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVendors()
    {
        return $this->vendors;
    }

    /**
     * Set css_class
     *
     * @param string $cssClass
     * @return Category
     */
    public function setCssClass($cssClass)
    {
        $this->css_class = $cssClass;

        return $this;
    }

    /**
     * Get css_class
     *
     * @return string 
     */
    public function getCssClass()
    {
        return $this->css_class;
    }

    /**
     * Set name_slug
     *
     * @param string $nameSlug
     * @return Category
     */
    public function setNameSlug($nameSlug)
    {
        $this->name_slug = $nameSlug;

        return $this;
    }

    /**
     * Get name_slug
     *
     * @return string 
     */
    public function getNameSlug()
    {
        return $this->name_slug;
    }

    /**
     * Add experiences
     *
     * @param \Zgh\FEBundle\Entity\Experience $experiences
     * @return Category
     */
    public function addExperience(\Zgh\FEBundle\Entity\Experience $experiences)
    {
        $this->experiences[] = $experiences;

        return $this;
    }

    /**
     * Remove experiences
     *
     * @param \Zgh\FEBundle\Entity\Experience $experiences
     */
    public function removeExperience(\Zgh\FEBundle\Entity\Experience $experiences)
    {
        $this->experiences->removeElement($experiences);
    }

    /**
     * Get experiences
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExperiences()
    {
        return $this->experiences;
    }
}
