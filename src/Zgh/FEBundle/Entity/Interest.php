<?php

namespace Zgh\FEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;

/**
 * @ORM\Entity
 * @ORM\Table(name="interests")
 */
class Interest
{
    use BasicInfo;

    /**
     * @ORM\ManyToMany(targetEntity="User")
     */
    protected $users;

    /**
     * @ORM\ManyToMany(targetEntity="Post")
     */
    protected $post;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->post = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add users
     *
     * @param \Zgh\FEBundle\Entity\User $users
     * @return Interest
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
     * Add post
     *
     * @param \Zgh\FEBundle\Entity\Post $post
     * @return Interest
     */
    public function addPost(\Zgh\FEBundle\Entity\Post $post)
    {
        $this->post[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \Zgh\FEBundle\Entity\Post $post
     */
    public function removePost(\Zgh\FEBundle\Entity\Post $post)
    {
        $this->post->removeElement($post);
    }

    /**
     * Get post
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPost()
    {
        return $this->post;
    }
}
