<?php

namespace Zgh\FEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zgh\FEBundle\Model\Partial\BasicInfo;

/**
 * @ORM\Entity
 * @ORM\Table(name="conf_replies")
 */
class ConfReply
{
    use BasicInfo;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="user_confs_replies")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Conf", inversedBy="conf_replies")
     */
    protected $conf;

    /**
     * @ORM\Column(type="text")
     */
    protected $content;




    /**
     * Set content
     *
     * @param string $content
     * @return ConfReply
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set user
     *
     * @param \Zgh\FEBundle\Entity\User $user
     * @return ConfReply
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
     * @return ConfReply
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
