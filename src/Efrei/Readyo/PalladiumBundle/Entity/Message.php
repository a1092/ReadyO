<?php

namespace Efrei\Readyo\PalladiumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table("PalladiumMessage")
 * @ORM\Entity(repositoryClass="Efrei\Readyo\PalladiumBundle\Entity\MessageRepository")
 */
class Message
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
     * @var \DateTime
     *
     * @ORM\Column(name="receivedAt", type="datetime")
     */
    private $receivedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="text")
     */
    private $data;

    /**
     * @var \Efrei\Readyo\PalladiumBundle\Entity\Application
     * 
     * @ORM\ManyToOne(targetEntity="Efrei\Readyo\PalladiumBundle\Entity\Application", inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $application;

    /**
     * @var \Efrei\Readyo\PalladiumBundle\Entity\Topic
     * 
     * @ORM\ManyToOne(targetEntity="Efrei\Readyo\PalladiumBundle\Entity\Topic", inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $topic;


    public function __construct() {
        $this->receivedAt = new \DateTime();
        $this->data = json_encode(array());
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
     * Set receivedAt
     *
     * @param \DateTime $receivedAt
     * @return Message
     */
    public function setReceivedAt($receivedAt)
    {
        $this->receivedAt = $receivedAt;

        return $this;
    }

    /**
     * Get receivedAt
     *
     * @return \DateTime 
     */
    public function getReceivedAt()
    {
        return $this->receivedAt;
    }

    /**
     * Set data
     *
     * @param string $data
     * @return Message
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return string 
     */
    public function getData()
    {
        return json_decode($this->data);
    }

    /**
     * Set application
     *
     * @param \Efrei\Readyo\PalladiumBundle\Entity\Application $application
     * @return Message
     */
    public function setApplication(\Efrei\Readyo\PalladiumBundle\Entity\Application $application)
    {
        $this->application = $application;

        return $this;
    }

    /**
     * Get application
     *
     * @return \Efrei\Readyo\PalladiumBundle\Entity\Application 
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Set topic
     *
     * @param \Efrei\Readyo\PalladiumBundle\Entity\Topic $topic
     * @return Message
     */
    public function setTopic(\Efrei\Readyo\PalladiumBundle\Entity\Topic $topic)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Get topic
     *
     * @return \Efrei\Readyo\PalladiumBundle\Entity\Topic 
     */
    public function getTopic()
    {
        return $this->topic;
    }
}
