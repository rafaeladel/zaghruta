<?php

namespace Zgh\FEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;

/**
 * @ORM\Entity(repositoryClass="Zgh\FEBundle\Repository\TagRepository")
 * @ORM\Table(name="tags")
 * @ORM\HasLifecycleCallbacks
 */
class Tag
{
    use BasicInfo;

    /**
     * @ORM\Column(type="text")
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $name_slug;

    /**
     * @ORM\ManyToMany(targetEntity="Product")
     */
    protected $products;

    /**
     * @ORM\ManyToMany(targetEntity="Zgh\FEBundle\Entity\User", inversedBy="tags")
     */
    protected $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add products
     *
     * @param \Zgh\FEBundle\Entity\Product $products
     * @return Tag
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
     * Set name
     *
     * @param string $name
     * @return Tag
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
     * Add users
     *
     * @param \Zgh\FEBundle\Entity\User $users
     * @return Tag
     */
    public function addUser(\Zgh\FEBundle\Entity\User $users)
    {
        $this->users[] = $users;
        $users->addTag($this);
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
