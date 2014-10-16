<?php

namespace Zgh\MsgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\MessageBundle\Entity\Thread as BaseThread;

/**
 * @ORM\Entity
 */
class Thread extends BaseThread
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Zgh\FEBundle\Entity\User")
     */
    protected $createdBy;

    /**
     * @ORM\OneToMany(
     *   targetEntity="Zgh\MsgBundle\Entity\Message",
     *   mappedBy="thread",
     *   cascade={"all"}
     * )
     * @var Message[]|\Doctrine\Common\Collections\Collection
     */
    protected $messages;

    /**
     * @ORM\OneToMany(
     *   targetEntity="Zgh\MsgBundle\Entity\ThreadMetadata",
     *   mappedBy="thread",
     *   cascade={"all"}
     * )
     * @var ThreadMetadata[]|\Doctrine\Common\Collections\Collection
     */
    protected $metadata;

    public function setSubject($subject)
    {
        $this->subject = "Conversation";
    }
}