<?php

namespace Efrei\Readyo\WebradioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Since;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;



/**
 * PodcastComment
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Efrei\Readyo\WebradioBundle\Entity\PodcastCommentRepository")
 * 
 * @ExclusionPolicy("all") 
 */
class PodcastComment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     * @Expose
     * @Groups({"list", "details"})
     * @Since("1.0")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * 
     * @Expose
     * @Groups({"list", "details"})
     * @SerializedName("content")
     * @Since("1.0")
     *
     */
    private $content;


    /**
     * @var \Efrei\Readyo\WebradioBundle\Entity\Podcast
     * 
     * @ORM\ManyToOne(targetEntity="Efrei\Readyo\WebradioBundle\Entity\Podcast", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $podcast;


    /**
     * @var \Efrei\Readyo\UserBundle\Entity\User
     * 
     * @ORM\ManyToOne(targetEntity="Efrei\Readyo\UserBundle\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=true)
     *
     * @Groups({"details", "podcast"})
     * @Since("1.0")
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publishAt", type="datetime")
     * 
     * @Expose
     * @Groups({"list", "details"})
     * @Type("DateTime") 
     * @Since("1.0")
     *
     */
    private $publishAt;


    /**
     * @var boolean
     *
     * @ORM\Column(name="isPublish", type="boolean")
     */
    private $isPublish;



    public function __construct() {
        $this->publishAt = new \DateTime();
    }

    /**
     * @VirtualProperty
     * @Type("string")
     * @SerializedName("podcastid")
     * @Groups({"list", "details"})
     * @Since("1.0")
     */
    public function Podcast(){
       
        return $this->podcast->getId();
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
     * Set content
     *
     * @param string $content
     * @return PodcastComment
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
     * Set publishAt
     *
     * @param \DateTime $publishAt
     * @return PodcastComment
     */
    public function setPublishAt($publishAt)
    {
        $this->publishAt = $publishAt;

        return $this;
    }

    /**
     * Get publishAt
     *
     * @return \DateTime 
     */
    public function getPublishAt()
    {
        return $this->publishAt;
    }

    /**
     * Set podcast
     *
     * @param \Efrei\Readyo\WebradioBundle\Entity\Podcast $podcast
     * @return PodcastComment
     */
    public function setPodcast(\Efrei\Readyo\WebradioBundle\Entity\Podcast $podcast)
    {
        $this->podcast = $podcast;

        return $this;
    }

    /**
     * Get podcast
     *
     * @return \Efrei\Readyo\WebradioBundle\Entity\Podcast 
     */
    public function getPodcast()
    {
        return $this->podcast;
    }

    /**
     * Set user
     *
     * @param \Efrei\Readyo\UserBundle\Entity\User $user
     * @return PodcastComment
     */
    public function setUser(\Efrei\Readyo\UserBundle\Entity\User $user = null)
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
     * Set isPublish
     *
     * @param boolean $isPublish
     * @return PodcastComment
     */
    public function setIsPublish($isPublish)
    {
        $this->isPublish = $isPublish;

        return $this;
    }

    /**
     * Get isPublish
     *
     * @return boolean 
     */
    public function getIsPublish()
    {
        return $this->isPublish;
    }
}
