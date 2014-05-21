<?php

namespace Zgh\FEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;

/**
 * @ORM\Entity
 * @ORM\Table(name="albums")
 * @ORM\HasLifecycleCallbacks
 */
class Album
{
    use BasicInfo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $info;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="albums")
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="Photo", mappedBy="album", cascade={"persist", "remove"})
     */
    protected $photos;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->photos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Album
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
     * Set user
     *
     * @param \Zgh\FEBundle\Entity\User $user
     * @return Album
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
     * Add photos
     *
     * @param \Zgh\FEBundle\Entity\Photo $photos
     * @return Album
     */
    public function addPhoto(\Zgh\FEBundle\Entity\Photo $photos)
    {
        $photos->setUser($this->getUser());
        $photos->setAlbum($this);
        $this->photos[] = $photos;

        return $this;
    }

    /**
     * Remove photos
     *
     * @param \Zgh\FEBundle\Entity\Photo $photos
     */
    public function removePhoto(\Zgh\FEBundle\Entity\Photo $photos)
    {
        $this->photos->removeElement($photos);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Set info
     *
     * @param string $info
     * @return Album
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
}
