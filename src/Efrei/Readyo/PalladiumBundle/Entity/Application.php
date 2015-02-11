<?php

namespace Efrei\Readyo\PalladiumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Efrei\Readyo\PalladiumBundle\Entity\ApplicationRepository")
 */
class Application
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="privateKey", type="string", length=255)
     */
    private $privateKey;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     *
     */
    private $active;

    /**
     * @var \Efrei\Readyo\PalladiumBundle\Entity\PalladiumMessage
     * 
     * @ORM\OneToMany(targetEntity="Efrei\Readyo\PalladiumBundle\Entity\PalladiumMessage", mappedBy="application")
     *
     */
    private $messages;


    /**
     * @var boolean
     *
     * @ORM\Column(name="connected", type="boolean")
     *
     */
    private $connected;

    /**
     * @var boolean
     *
     * @ORM\Column(name="multiconnexion", type="boolean")
     *
     */
    private $multiconnexion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastActivity", type="datetime", nullable=true)
     */
    private $lastActivity;




    private $authenticated;
    private $topics;
    private $topicsMatcher;
    private $client;


    public function __construct() {
        $this->topics = new \Doctrine\Common\Collections\ArrayCollection();
        $this->topicsMatcher = new \Doctrine\Common\Collections\ArrayCollection();
        $this->authenticated = false;
        $this->client = null;
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
     * Set name
     *
     * @param string $name
     * @return Application
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
     * Set description
     *
     * @param string $description
     * @return Application
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
     * Set privateKey
     *
     * @param string $privateKey
     * @return Application
     */
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;

        return $this;
    }

    /**
     * Get privateKey
     *
     * @return string 
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Application
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function isActive()
    {
        return $this->active;
    }




    /**
     * Add topic
     *
     * @param \Efrei\Readyo\PalladiumBundle\Entity\Topic $topic
     * @return Application
     */
    public function addTopic(\Efrei\Readyo\PalladiumBundle\Entity\Topic $topic)
    {
        $this->topics[] = $topic;

        return $this;
    }

    /**
     * Remove topic
     *
     * @param \Efrei\Readyo\WebradioBundle\Entity\Schedule $schedules
     */
    public function removeTopic(\Efrei\Readyo\PalladiumBundle\Entity\Topic $topic)
    {  
        $this->topics->removeElement($topic);
    }

    /**
     * Get topics
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTopics()
    {
        return $this->topics;
    }


    public function addTopicMatcher($matcher)
    {

        $this->topicsMatcher[] = $matcher;

        return $this;
    }

    public function removeTopicMatcher($matcher)
    {  
        $this->topicsMatcher->removeElement($matcher);
    }

    public function getTopicsMatcher()
    {
        return $this->topicsMatcher;
    }

    public function isAuthenticated() {
        return $this->authenticated;
    }

    public function setAuthenticated($authenticated) {
        $this->authenticated = $authenticated;
    }

    /**
     * Add messages
     *
     * @param \Efrei\Readyo\PalladiumBundle\Entity\PalladiumMessage $messages
     * @return Application
     */
    public function addMessage(\Efrei\Readyo\PalladiumBundle\Entity\PalladiumMessage $messages)
    {
        $this->messages[] = $messages;

        return $this;
    }

    /**
     * Remove messages
     *
     * @param \Efrei\Readyo\PalladiumBundle\Entity\PalladiumMessage $messages
     */
    public function removeMessage(\Efrei\Readyo\PalladiumBundle\Entity\PalladiumMessage $messages)
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


    public function setClient($client) {
        $this->client = $client;
    }

    public function getClient() {
        return $this->client;
    }

    public function __toString() {
        return $this->getName();
    }

    /**
     * Set connected
     *
     * @param boolean $connected
     * @return Application
     */
    public function setConnected($connected)
    {
        $this->connected = $connected;

        return $this;
    }

    /**
     * Get connected
     *
     * @return boolean 
     */
    public function isConnected()
    {
        return $this->connected;
    }

    /**
     * Set lastActivity
     *
     * @param \DateTime $lastActivity
     * @return Application
     */
    public function setLastActivity($lastActivity)
    {
        $this->lastActivity = $lastActivity;

        return $this;
    }

    /**
     * Get lastActivity
     *
     * @return \DateTime 
     */
    public function getLastActivity()
    {
        return $this->lastActivity;
    }

    /**
     * Set multiconnexion
     *
     * @param boolean $multiconnexion
     * @return Application
     */
    public function setMulticonnexion($multiconnexion)
    {
        $this->multiconnexion = $multiconnexion;

        return $this;
    }

    /**
     * Get multiconnexion
     *
     * @return boolean 
     */
    public function getMulticonnexion()
    {
        return $this->multiconnexion;
    }
}
