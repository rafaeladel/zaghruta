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
     * @ORM\ManyToMany(targetEntity="Zgh\FEBundle\Entity\Experience")
     */
    protected $experiences;

    /**
     * @ORM\ManyToMany(targetEntity="Product")
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
     * Constructor
     */
    public function __construct()
    {
        $this->experiences = new \Doctrine\Common\Collections\ArrayCollection();
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
}
