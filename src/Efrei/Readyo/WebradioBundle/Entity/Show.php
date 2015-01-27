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

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * Show
 *
 * @ORM\Table("Emission")
 * @ORM\Entity(repositoryClass="Efrei\Readyo\WebradioBundle\Entity\ShowRepository")
 * @ORM\HasLifecycleCallbacks()
 * 
 * @ExclusionPolicy("all") 
 */
class Show
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
     * @SerializedName("title")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="shortTitle", type="string", length=200)
     *
     * @Expose
     * @Groups({"list", "details"})
     * @Since("1.0")
     * @SerializedName("shortTitle")
     */
    private $shortTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="subtitle", type="string", length=200)
     *
     * @Expose
     * @Groups({"list", "details"})
     * @Since("1.0")
     * @SerializedName("subTitle")
     */
    private $subTitle;


    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=250)
     *
     * @Expose
     * @Groups({"list", "details"})
     * @Since("1.0")
     * @SerializedName("description")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=250)
     *
     * @Expose
     * @Groups({"list", "details"})
     * @Since("1.0")
     * @SerializedName("type")
     */
    private $type;



    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bigPicturePath;

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $bigPictureFile;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $smallPicturePath;

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $smallPictureFile;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modifiedTime", type="datetime", nullable=true)
     */
    private $modifiedTime;



    /**
     * @var \Efrei\Readyo\WebradioBundle\Entity\Programme
     * 
     * @ORM\OneToMany(targetEntity="Efrei\Readyo\WebradioBundle\Entity\Schedule", mappedBy="show")
     *
     * @Expose
     * @Groups({"details"})
     * @Since("1.0")
     * @SerializedName("schedules")
     */
    private $schedules;


    /**
     * @var boolean
     *
     * @ORM\Column(name="isPublish", type="boolean")
     */
    private $isPublish;


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
        $this->schedules = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Show
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
     * Set shortTitle
     *
     * @param string $shortTitle
     * @return Show
     */
    public function setShortTitle($shortTitle)
    {
        $this->shortTitle = $shortTitle;

        return $this;
    }

    /**
     * Get shortTitle
     *
     * @return string 
     */
    public function getShortTitle()
    {
        return $this->shortTitle;
    }

    /**
     * Set subTitle
     *
     * @param string $subTitle
     * @return Show
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
     * Set description
     *
     * @param string $description
     * @return Show
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
     * Set isPublish
     *
     * @param boolean $isPublish
     * @return Show
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
     * Add schedules
     *
     * @param \Efrei\Readyo\WebradioBundle\Entity\Schedule $schedules
     * @return Show
     */
    public function addSchedule(\Efrei\Readyo\WebradioBundle\Entity\Schedule $schedules)
    {
        $this->schedules[] = $schedules;

        return $this;
    }

    /**
     * Remove schedules
     *
     * @param \Efrei\Readyo\WebradioBundle\Entity\Schedule $schedules
     */
    public function removeSchedule(\Efrei\Readyo\WebradioBundle\Entity\Schedule $schedules)
    {
        $this->schedules->removeElement($schedules);
    }

    /**
     * Get schedules
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSchedules()
    {
        return $this->schedules;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Show
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
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {

        if ($this->smallPictureFile !== null) {
            $this->removePicture($this->getSmallPictureAbsolutePath());
            $this->smallPicturePath = sha1(uniqid(mt_rand(), true)).'.'.$this->smallPictureFile->guessExtension();
        }

        if ($this->bigPictureFile !== null) {
            $this->removePicture($this->getBigPictureAbsolutePath());
            $this->bigPicturePath = sha1(uniqid(mt_rand(), true)).'.'.$this->bigPictureFile->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {

        if ($this->smallPictureFile !== null) {
            $this->smallPictureFile->move($this->getUploadRootDir(), $this->smallPicturePath);
            unset($this->smallPictureFile);
        }

        if ($this->bigPictureFile !== null) {
            $this->bigPictureFile->move($this->getUploadRootDir(), $this->bigPicturePath);
            unset($this->bigPictureFile);
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        $this->removePicture($this->getSmallPictureAbsolutePath());
        $this->removePicture($this->getBigPictureAbsolutePath());
    }


    private function removePicture($file) {

        if(!empty($file))
            unlink($file);
    }


    
    public function getSmallPictureAbsolutePath()
    {
        return null === $this->smallPicturePath ? null : $this->getUploadRootDir().'/'.$this->smallPicturePath;
    }

    /**
     * @VirtualProperty
     * @Type("string")
     * @SerializedName("picture_lg")
     * @Groups({"list", "details"})
     */
    public function getSmallPictureWebPath()
    {
        return null === $this->smallPicturePath ? null : $this->getUploadDir().'/'.$this->smallPicturePath;
    }

    public function getBigPictureAbsolutePath()
    {
        return null === $this->bigPicturePath ? null : $this->getUploadRootDir().'/'.$this->bigPicturePath;
    }

    /**
     * @VirtualProperty
     * @Type("string")
     * @SerializedName("picture_sm")
     * @Groups({"list", "details"})
     */
    public function getBigPictureWebPath()
    {
        return null === $this->bigPicturePath ? null : $this->getUploadDir().'/'.$this->bigPicturePath;
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
        return 'data/images';
    }


    /**
     * Set bigPicturePath
     *
     * @param string $bigPicturePath
     * @return Show
     */
    public function setBigPicturePath($bigPicturePath)
    {
        $this->bigPicturePath = $bigPicturePath;

        return $this;
    }

    /**
     * Get bigPicturePath
     *
     * @return string 
     */
    public function getBigPicturePath()
    {
        return $this->bigPicturePath;
    }

    /**
     * Set smallPicturePath
     *
     * @param string $smallPicturePath
     * @return Show
     */
    public function setSmallPicturePath($smallPicturePath)
    {
        $this->smallPicturePath = $smallPicturePath;

        return $this;
    }

    /**
     * Get smallPicturePath
     *
     * @return string 
     */
    public function getSmallPicturePath()
    {
        return $this->smallPicturePath;
    }

    /**
     * Set modifiedTime
     *
     * @param \DateTime $modifiedTime
     * @return Show
     */
    public function setModifiedTime($modifiedTime)
    {
        $this->modifiedTime = $modifiedTime;

        return $this;
    }

    /**
     * Get modifiedTime
     *
     * @return \DateTime 
     */
    public function getModifiedTime()
    {
        return $this->modifiedTime;
    }
}
