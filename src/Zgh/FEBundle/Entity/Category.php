<?php

namespace Zgh\FEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;

/**
 * @ORM\Entity(repositoryClass="Zgh\FEBundle\Repository\CategoryRepository")
 * @ORM\Table(name="categories")
 * @ORM\HasLifecycleCallbacks
 */
class Category
{
    use BasicInfo;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent_category", cascade={"persist", "remove"})
     **/
    protected $sub_categories;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="sub_categories")
     **/
    protected $parent_category;

    /**
     * @ORM\ManyToMany(targetEntity="Experience", mappedBy="categories")
     */
    protected $experiences;

    /**
     * @ORM\ManyToMany(targetEntity="Tip", mappedBy="categories")
     */
    protected $tips;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="interests")
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
     * @ORM\Column(type="boolean")
     */
    protected $is_hidden;

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

    /**
     * Add sub_categories
     *
     * @param \Zgh\FEBundle\Entity\Category $subCategories
     * @return Category
     */
    public function addSubCategory(\Zgh\FEBundle\Entity\Category $subCategories)
    {
        $this->sub_categories[] = $subCategories;
        $subCategories->setParentCategory($this);
        return $this;
    }

    /**
     * Remove sub_categories
     *
     * @param \Zgh\FEBundle\Entity\Category $subCategories
     */
    public function removeSubCategory(\Zgh\FEBundle\Entity\Category $subCategories)
    {
        $this->sub_categories->removeElement($subCategories);
    }

    /**
     * Get sub_categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubCategories()
    {
        return $this->sub_categories;
    }

    /**
     * Set parent_category
     *
     * @param \Zgh\FEBundle\Entity\Category $parentCategory
     * @return Category
     */
    public function setParentCategory(\Zgh\FEBundle\Entity\Category $parentCategory = null)
    {
        $this->parent_category = $parentCategory;

        return $this;
    }

    /**
     * Get parent_category
     *
     * @return \Zgh\FEBundle\Entity\Category 
     */
    public function getParentCategory()
    {
        return $this->parent_category;
    }

    /**
     * Set is_hidden
     *
     * @param boolean $isHidden
     * @return Category
     */
    public function setIsHidden($isHidden)
    {
        $this->is_hidden = $isHidden;

        return $this;
    }

    /**
     * Get is_hidden
     *
     * @return boolean 
     */
    public function getIsHidden()
    {
        return $this->is_hidden;
    }

    /**
     * Add tips
     *
     * @param \Zgh\FEBundle\Entity\Tip $tips
     * @return Category
     */
    public function addTip(\Zgh\FEBundle\Entity\Tip $tips)
    {
        $this->tips[] = $tips;

        return $this;
    }

    /**
     * Remove tips
     *
     * @param \Zgh\FEBundle\Entity\Tip $tips
     */
    public function removeTip(\Zgh\FEBundle\Entity\Tip $tips)
    {
        $this->tips->removeElement($tips);
    }

    /**
     * Get tips
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTips()
    {
        return $this->tips;
    }
}
