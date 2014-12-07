<?php

namespace Zgh\MsgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\MessageBundle\Entity\Message as BaseMessage;
use Zgh\MsgBundle\Entity\MessageMetadata;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\ThreadInterface;

/**
 * @ORM\Entity
 */
class Message extends BaseMessage
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="Zgh\MsgBundle\Entity\Thread",
     *   inversedBy="messages"
     * )
     * @var ThreadInterface
     */
    protected $thread;

    /**
     * @ORM\ManyToOne(targetEntity="Zgh\FEBundle\Entity\User")
     * @var ParticipantInterface
     */
    protected $sender;

    /**
     * @ORM\OneToMany(
     *   targetEntity="Zgh\MsgBundle\Entity\MessageMetadata",
     *   mappedBy="message",
     *   cascade={"all"}
     * )
     * @var MessageMetadata
     */
    protected $metadata;

    /**
     * @ORM\OneToMany(targetEntity="Zgh\MsgBundle\Entity\DeletedMessage", mappedBy="message", cascade={"all"})
     */
    protected $delete_table;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_already_deleted;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->delete_table = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add delete_table
     *
     * @param \Zgh\MsgBundle\Entity\DeletedMessage $deleteTable
     * @return Message
     */
    public function addDeleteTable(\Zgh\MsgBundle\Entity\DeletedMessage $deleteTable)
    {
        $this->delete_table[] = $deleteTable;

        return $this;
    }

    /**
     * Remove delete_table
     *
     * @param \Zgh\MsgBundle\Entity\DeletedMessage $deleteTable
     */
    public function removeDeleteTable(\Zgh\MsgBundle\Entity\DeletedMessage $deleteTable)
    {
        $this->delete_table->removeElement($deleteTable);
    }

    /**
     * Get delete_table
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDeleteTable()
    {
        return $this->delete_table;
    }

    /**
     * Set is_already_deleted
     *
     * @param boolean $isAlreadyDeleted
     * @return Message
     */
    public function setIsAlreadyDeleted($isAlreadyDeleted)
    {
        $this->is_already_deleted = $isAlreadyDeleted;

        return $this;
    }

    /**
     * Get is_already_deleted
     *
     * @return boolean 
     */
    public function getIsAlreadyDeleted()
    {
        return $this->is_already_deleted;
    }
}
