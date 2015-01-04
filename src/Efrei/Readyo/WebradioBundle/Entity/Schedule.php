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
 * Schedule
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Efrei\Readyo\WebradioBundle\Entity\ScheduleRepository")
 * 
 * @ExclusionPolicy("all") 
 */
class Schedule
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
     * @ORM\Column(name="title", type="string", length=200)
     *
     * @Expose
     * @Groups({"list", "details"})
     * @Since("1.0")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="subTitle", type="string", length=200)
     *
     * @Expose
     * @Groups({"list", "details"})
     * @Since("1.0")
     */
    private $subTitle;


    /**
     * @var \Efrei\Readyo\WebradioBundle\Entity\Show
     * 
     * @ORM\ManyToOne(targetEntity="Efrei\Readyo\WebradioBundle\Entity\Show", inversedBy="schedules")
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $show;


    /**
    * @var text $summary
    *
    * @ORM\Column(name="summary", type="text")
    *
     * @Expose
     * @Groups({"list", "details"})
     * @Since("1.0")
     * @SerializedName("summary")
    */
    private $summary;


   /**
    * @var text $summary
    *
    * @ORM\Column(name="guests", type="text")
    *
     * @Expose
     * @Groups({"list", "details"})
     * @Since("1.0")
     * @SerializedName("guests")
    */
    private $guests;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="diffusedAt", type="datetime")
     *
     * @Expose
     * @Groups({"list", "details"})
     * @Since("1.0")
     */
    private $diffusedAt;

 
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finishedAt", type="datetime")
     *
     * @Expose
     * @Groups({"list", "details"})
     * @Since("1.0")
     */
    private $finishedAt;


    /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer")
     *
     * @Expose
     * @Groups({"list", "details"})
     * @Since("1.0")
     */
    private $duration;


    /**
     * @var boolean
     *
     * @ORM\Column(name="isPublish", type="boolean")
     */
    private $isPublish;


    /**
     * @var boolean
     *
     * @ORM\Column(name="isLive", type="boolean")
     *
     * @Expose
     * @Groups({"list", "details"})
     * @Since("1.0")
     */
    private $isLive;


    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Efrei\Readyo\WebradioBundle\Entity\Podcast", mappedBy="schedule")
     *
     * @Expose
     * @Groups({"details"})
     * @Since("1.0")
     */
    private $podcasts;


    /**
     * @var \Efrei\Readyo\MusicBundle\Entity\MusicPlayed
     * 
     * @ORM\OneToMany(targetEntity="Efrei\Readyo\MusicBundle\Entity\MusicPlayed", mappedBy="schedule")
     *
     * @Expose
     * @Groups({"details"})
     * @Since("1.0")
     */
    private $playlist;


    /**
     * @var string
     *
     * @ORM\Column(name="spotifyUri", type="string", length=200)
     *
     * @Expose
     * @Groups({"list", "details"})
     * @Since("1.0")
     */
    private $spotifyUri;

 
    /**
    * @ORM\ManyToMany(targetEntity="Efrei\Readyo\PalladiumBundle\Entity\Output", inversedBy="schedules")
    * @ORM\JoinTable(name="output_schedules")
    */
    private $outputs;


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
     * Constructor
     */
    public function __construct()
    {
        $this->podcasts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->playlist = new \Doctrine\Common\Collections\ArrayCollection();
        $this->outputs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Schedule
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set subTitle
     *
     * @param string $subTitle
     * @return Schedule
     */
    public function setSubTitle($subTitle)
    {
        $this->subTitle = $subTitle;

        return $this;
    }

    /**
     * Get subTitle
     *
     * @return string 
     */
    public function getSubTitle()
    {
        return $this->subTitle;
    }

    /**
     * Set summary
     *
     * @param string $summary
     * @return Schedule
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string 
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set guests
     *
     * @param string $guests
     * @return Schedule
     */
    public function setGuests($guests)
    {
        $this->guests = $guests;

        return $this;
    }

    /**
     * Get guests
     *
     * @return string 
     */
    public function getGuests()
    {
        return $this->guests;
    }

    /**
     * Set diffusedAt
     *
     * @param \DateTime $diffusedAt
     * @return Schedule
     */
    public function setDiffusedAt($diffusedAt)
    {
        $this->diffusedAt = $diffusedAt;

        $this->updateFinishedAt();

        return $this;
    }

    /**
     * Get diffusedAt
     *
     * @return \DateTime 
     */
    public function getDiffusedAt()
    {
        return $this->diffusedAt;
    }

    /**
     * Set finishedAt
     *
     * @param \DateTime $finishedAt
     * @return Schedule
     */
    public function setFinishedAt($finishedAt)
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }

    /**
     * Get finishedAt
     *
     * @return \DateTime 
     */
    public function getFinishedAt()
    {
        return $this->finishedAt;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     * @return Schedule
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        $this->updateFinishedAt();
        
        return $this;
    }

    /**
     * Get duration
     *
     * @return integer 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set isPublish
     *
     * @param boolean $isPublish
     * @return Schedule
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

    /**
     * Set spotifyUri
     *
     * @param string $spotifyUri
     * @return Schedule
     */
    public function setSpotifyUri($spotifyUri)
    {
        $this->spotifyUri = $spotifyUri;

        return $this;
    }

    /**
     * Get spotifyUri
     *
     * @return string 
     */
    public function getSpotifyUri()
    {
        return $this->spotifyUri;
    }

    /**
     * Set show
     *
     * @param \Efrei\Readyo\WebradioBundle\Entity\Show $show
     * @return Schedule
     */
    public function setShow(\Efrei\Readyo\WebradioBundle\Entity\Show $show)
    {
        $this->show = $show;

        return $this;
    }

    /**
     * Get show
     *
     * @return \Efrei\Readyo\WebradioBundle\Entity\Show 
     */
    public function getShow()
    {
        return $this->show;
    }

    /**
     * Add podcasts
     *
     * @param \Efrei\Readyo\WebradioBundle\Entity\Podcast $podcasts
     * @return Schedule
     */
    public function addPodcast(\Efrei\Readyo\WebradioBundle\Entity\Podcast $podcasts)
    {
        $this->podcasts[] = $podcasts;

        return $this;
    }

    /**
     * Remove podcasts
     *
     * @param \Efrei\Readyo\WebradioBundle\Entity\Podcast $podcasts
     */
    public function removePodcast(\Efrei\Readyo\WebradioBundle\Entity\Podcast $podcasts)
    {
        $this->podcasts->removeElement($podcasts);
    }

    /**
     * Get podcasts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPodcasts()
    {
        return $this->podcasts;
    }

    /**
     * Add playlist
     *
     * @param \Efrei\Readyo\MusicBundle\Entity\MusicPlayed $playlist
     * @return Schedule
     */
    public function addPlaylist(\Efrei\Readyo\MusicBundle\Entity\MusicPlayed $playlist)
    {
        $this->playlist[] = $playlist;

        return $this;
    }

    /**
     * Remove playlist
     *
     * @param \Efrei\Readyo\MusicBundle\Entity\MusicPlayed $playlist
     */
    public function removePlaylist(\Efrei\Readyo\MusicBundle\Entity\MusicPlayed $playlist)
    {
        $this->playlist->removeElement($playlist);
    }

    /**
     * Get playlist
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlaylist()
    {
        return $this->playlist;
    }

    public function __toString()
    {
        return "[".$this->getShow()->getTitle()."] ".$this->getTitle();
    }

    /**
     * Set isLive
     *
     * @param boolean $isLive
     * @return Schedule
     */
    public function setIsLive($isLive)
    {
        $this->isLive = $isLive;

        return $this;
    }

    /**
     * Get isLive
     *
     * @return boolean 
     */
    public function getIsLive()
    {
        return $this->isLive;
    }


    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    private function updateFinishedAt() {

        $finishedAt = new \DateTime();
        $finishedAt->setTimestamp($this->getDiffusedAt()->getTimestamp()+$this->getDuration()*60);
        $this->setFinishedAt($finishedAt);

        return $finishedAt;
    }

    /**
     * Add outputs
     *
     * @param \Efrei\Readyo\PalladiumBundle\Entity\Output $outputs
     * @return Schedule
     */
    public function addOutput(\Efrei\Readyo\PalladiumBundle\Entity\Output $outputs)
    {
        $output->addSchedule($outputs);

        $this->outputs[] = $outputs;

        return $this;
    }

    /**
     * Remove outputs
     *
     * @param \Efrei\Readyo\PalladiumBundle\Entity\Output $outputs
     */
    public function removeOutput(\Efrei\Readyo\PalladiumBundle\Entity\Output $outputs)
    {
        $this->outputs->removeElement($outputs);
    }

    /**
     * Get outputs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOutputs()
    {
        return $this->outputs;
    }
}
