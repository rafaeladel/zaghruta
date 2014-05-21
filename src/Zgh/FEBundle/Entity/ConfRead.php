<?php

namespace Zgh\FEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;

/**
 * @ORM\Entity
 * @ORM\Table(name="conf_reads")
 */
class ConfRead
{
    use BasicInfo;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="c_read")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Conf")
     */
    protected $conf;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $read_status;


    /**
     * Set read_status
     *
     * @param boolean $readStatus
     * @return ConfRead
     */
    public function setReadStatus($readStatus)
    {
        $this->read_status = $readStatus;

        return $this;
    }

    /**
     * Get read_status
     *
     * @return boolean 
     */
    public function getReadStatus()
    {
        return $this->read_status;
    }

    /**
     * Set user
     *
     * @param \Zgh\FEBundle\Entity\User $user
     * @return ConfRead
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
     * Set conf
     *
     * @param \Zgh\FEBundle\Entity\Conf $conf
     * @return ConfRead
     */
    public function setConf(\Zgh\FEBundle\Entity\Conf $conf = null)
    {
        $this->conf = $conf;

        return $this;
    }

    /**
     * Get conf
     *
     * @return \Zgh\FEBundle\Entity\Conf 
     */
    public function getConf()
    {
        return $this->conf;
    }
}
