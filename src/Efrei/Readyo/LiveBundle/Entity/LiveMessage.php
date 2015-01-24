<?php

namespace Efrei\Readyo\LiveBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Since;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

/**
 * Message
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Efrei\Readyo\LiveBundle\Entity\MessageRepository")
 *
 * @ExclusionPolicy("all") 
 */
class LiveMessage
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     *
     * @Expose
     * @Groups({"live"})
     * @Since("1.0")
     */
    private $text;


    /**
     * @var \Efrei\Readyo\WebradioBundle\Entity\Schedule
     * 
     * @ORM\ManyToOne(targetEntity="Efrei\Readyo\WebradioBundle\Entity\Schedule", inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $schedule;


    /**
     * @var \Efrei\Readyo\UserBundle\Entity\User
     * 
     * @ORM\ManyToOne(targetEntity="Efrei\Readyo\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Expose
     * @Groups({"live"})
     * @Since("1.0")
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sendAt", type="datetime")
     *
     * @Expose
     * @Groups({"live"})
     * @Since("1.0")
     */
    private $sendAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isFlag", type="boolean")
     */
    private $isFlag;


    public function __construct() {
        $this->sendAt = new \Datetime();
        $this->isFlag = false;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set text
     *
     * @param string $text
     * @return Message
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set sendAt
     *
     * @param \DateTime $sendAt
     * @return Message
     */
    public function setSendAt($sendAt)
    {
        $this->sendAt = $sendAt;

        return $this;
    }

    /**
     * Get sendAt
     *
     * @return \DateTime 
     */
    public function getSendAt()
    {
        return $this->sendAt;
    }

    /**
     * Set isFlag
     *
     * @param boolean $isFlag
     * @return Message
     */
    public function setIsFlag($isFlag)
    {
        $this->isFlag = $isFlag;

        return $this;
    }

    /**
     * Get isFlag
     *
     * @return boolean 
     */
    public function getIsFlag()
    {
        return $this->isFlag;
    }

    /**
     * Set user
     *
     * @param \Efrei\Readyo\UserBundle\Entity\User $user
     * @return Message
     */
    public function setUser(\Efrei\Readyo\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Efrei\Readyo\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set schedule
     *
     * @param \Efrei\Readyo\WebradioBundle\Entity\Schedule $schedule
     * @return Message
     */
    public function setSchedule(\Efrei\Readyo\WebradioBundle\Entity\Schedule $schedule)
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * Get schedule
     *
     * @return \Efrei\Readyo\WebradioBundle\Entity\Schedule 
     */
    public function getSchedule()
    {
        return $this->schedule;
    }
}
