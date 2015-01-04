<?php

namespace Efrei\Readyo\PalladiumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Topic
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Efrei\Readyo\PalladiumBundle\Entity\TopicRepository")
 */
class Topic
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
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="format", type="text", nullable=true)
     */
    private $format;

    /**
     * @var \Efrei\Readyo\PalladiumBundle\Entity\Message
     * 
     * @ORM\OneToMany(targetEntity="Efrei\Readyo\PalladiumBundle\Entity\Message", mappedBy="topic")
     *
     */
    private $messages;

    /**
     * @var boolean
     *
     * @ORM\Column(name="log", type="boolean")
     *
     */
    private $log;


    private $applications;


    public function __construct() {
        $this->applications = array();
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
     * Set path
     *
     * @param string $path
     * @return Topic
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Topic
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set format
     *
     * @param string $format
     * @return Topic
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get format
     *
     * @return string 
     */
    public function getFormat()
    {
        return $this->format;
    }


    /**
     * Add application
     *
     * @param \Efrei\Readyo\PalladiumBundle\Entity\Application $application
     * @return Topic
     */
    public function addApplication(\Efrei\Readyo\PalladiumBundle\Entity\Application $application)
    {
        $this->applications[$application->getId()] = $application;

        return $this;
    }

    /**
     * Remove application
     *
     * @param \Efrei\Readyo\PalladiumBundle\Entity\Application $application
     */
    public function removeApplication(\Efrei\Readyo\PalladiumBundle\Entity\Application $application)
    {  
        if(array_key_exists($application->getId(), $this->applications))
            unset($this->applications[$application->getId()]);
    }

    /**
     * Get applications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * Add messages
     *
     * @param \Efrei\Readyo\PalladiumBundle\Entity\Message $messages
     * @return Topic
     */
    public function addMessage(\Efrei\Readyo\PalladiumBundle\Entity\Message $messages)
    {
        $this->messages[] = $messages;

        return $this;
    }

    /**
     * Remove messages
     *
     * @param \Efrei\Readyo\PalladiumBundle\Entity\Message $messages
     */
    public function removeMessage(\Efrei\Readyo\PalladiumBundle\Entity\Message $messages)
    {
        $this->messages->removeElement($messages);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages()
    {
        return $this->messages;
    }

    public function __toString() {
        return $this->path;
    }

    /**
     * Set log
     *
     * @param boolean $log
     * @return Topic
     */
    public function setLog($log)
    {
        $this->log = $log;

        return $this;
    }

    /**
     * Get log
     *
     * @return boolean 
     */
    public function getLog()
    {
        return $this->log;
    }
}
