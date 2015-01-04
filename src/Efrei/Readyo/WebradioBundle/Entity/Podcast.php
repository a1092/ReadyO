<?php

namespace Efrei\Readyo\WebradioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;


use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Since;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\MaxDepth;


/**
 * Podcast
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Efrei\Readyo\WebradioBundle\Entity\PodcastRepository")
 * @ORM\HasLifecycleCallbacks
 * 
 * @ExclusionPolicy("all") 
 */
class Podcast
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;


    public $file;

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
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer")
     *
     * @Expose
     * @Groups({"list", "details"})
     * @Since("1.0")
     * @SerializedName("duration")
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=200)
     * 
     * @Expose
     * @Groups({"list", "details"})
     * @Since("1.0")
     * @SerializedName("type")
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="listen", type="integer", nullable=true)
     *
     * @Expose
     * @Groups({"list", "details"})
     * @Since("1.0")
     * @SerializedName("listen")
     */
    private $listen;

    /**
     * @var \Efrei\Readyo\WebradioBundle\Entity\PodcastComment
     * 
     * @ORM\OneToMany(targetEntity="Efrei\Readyo\WebradioBundle\Entity\PodcastComment", mappedBy="podcast")
     *
     */
    private $comments;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="uploadAt", type="datetime")
     *
     * @Expose
     * @Groups({"list", "details"})
     * @Since("1.0")
     */
    private $uploadAt;


    /**
     * @var boolean
     *
     * @ORM\Column(name="isPublish", type="boolean")
     */
    private $isPublish;


    /**
     * @var \Efrei\Readyo\WebradioBundle\Entity\Schedule
     * 
     * @ORM\ManyToOne(targetEntity="Efrei\Readyo\WebradioBundle\Entity\Schedule", inversedBy="podcasts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $schedule;



    /**
     * @VirtualProperty
     * @Type("string")
     * @SerializedName("href")
     * @Groups({"list", "details"})
     */
    public function href() {
        if($this->getWebPath())
            return $this->getWebPath();
        else
            return null;
    }



    /**
     * @VirtualProperty
     * @Type("integer")
     * @SerializedName("scheduleid")
     * @Groups({"list", "details"})
     */
    public function scheduleId() {
        

        return $this->schedule->getId();
    }



    public function __construct() {
        $this->uploadAt = new \DateTime();
        $this->listen = 0;
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

    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'upload/podcast';
    }


    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            // faites ce que vous voulez pour générer un nom unique
            $this->path = sha1(uniqid(mt_rand(), true)).'.'.$this->file->getExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        // s'il y a une erreur lors du déplacement du fichier, une exception
        // va automatiquement être lancée par la méthode move(). Cela va empêcher
        // proprement l'entité d'être persistée dans la base de données si
        // erreur il y a
        $this->file->move($this->getUploadRootDir(), $this->path);

        unset($this->file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Image
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

    public function __toString() {
        
        if($this->getPath())
            return $this->getPath();
        else
            return "";
    }

    /**
     * Set duration
     *
     * @param integer $duration
     * @return Podcast
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

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
     * Set uploadAt
     *
     * @param \DateTime $uploadAt
     * @return Podcast
     */
    public function setUploadAt($uploadAt)
    {
        $this->uploadAt = $uploadAt;

        return $this;
    }

    /**
     * Get uploadAt
     *
     * @return \DateTime 
     */
    public function getUploadAt()
    {
        return $this->uploadAt;
    }

    /**
     * Set isPublish
     *
     * @param boolean $isPublish
     * @return Podcast
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
     * Set listen
     *
     * @param integer $listen
     * @return Podcast
     */
    public function setListen($listen)
    {
        $this->listen = $listen;

        return $this;
    }

    /**
     * Get listen
     *
     * @return integer 
     */
    public function getListen()
    {
        return $this->listen;
    }

    /**
     * Set schedule
     *
     * @param \Efrei\Readyo\WebradioBundle\Entity\Schedule $schedule
     * @return Podcast
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

    /**
     * Set type
     *
     * @param string $type
     * @return Podcast
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add comments
     *
     * @param \Efrei\Readyo\WebradioBundle\Entity\PodcastComment $comments
     * @return Podcast
     */
    public function addComment(\Efrei\Readyo\WebradioBundle\Entity\PodcastComment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Efrei\Readyo\WebradioBundle\Entity\PodcastComment $comments
     */
    public function removeComment(\Efrei\Readyo\WebradioBundle\Entity\PodcastComment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

  

    /**
     * Set title
     *
     * @param string $title
     * @return Podcast
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
}
