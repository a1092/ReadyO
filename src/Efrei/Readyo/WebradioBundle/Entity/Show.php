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
 * Show2
 *
 * @ORM\Table("Emission")
 * @ORM\Entity(repositoryClass="Efrei\Readyo\WebradioBundle\Entity\ShowRepository")
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
     * @var array
     *
     * @ORM\OneToOne(targetEntity="Efrei\Readyo\WebradioBundle\Entity\Image", cascade={"persist"})
     *
     */
    private $pictureBig;


    /**
     * @var array
     *
     * @ORM\OneToOne(targetEntity="Efrei\Readyo\WebradioBundle\Entity\Image", cascade={"persist"})
     *
     */
    private $pictureSmall;



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
     * @VirtualProperty
     * @Type("string")
     * @SerializedName("pictureBig")
     * @Groups({"list", "details"})
     * @Since("1.0")
     */
    public function pictureBig() {
        if($this->pictureBig)
            return $this->pictureBig->getWebPath();
        else
            return "aa";
    }

    /**
     * @VirtualProperty
     * @Type("string")
     * @SerializedName("pictureSmall")
     * @Groups({"list", "details"})
     * @Since("1.0")
     */
    public function pictureSmall() {
        if($this->pictureSmall)
            return $this->pictureSmall->getWebPath();
        else
            return "aa";
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
     * Set pictureBig
     *
     * @param \Efrei\Readyo\WebradioBundle\Entity\Image $pictureBig
     * @return Show
     */
    public function setPictureBig(\Efrei\Readyo\WebradioBundle\Entity\Image $pictureBig = null)
    {
        $this->pictureBig = $pictureBig;

        return $this;
    }

    /**
     * Get pictureBig
     *
     * @return \Efrei\Readyo\WebradioBundle\Entity\Image 
     */
    public function getPictureBig()
    {
        return $this->pictureBig;
    }

    /**
     * Set pictureSmall
     *
     * @param \Efrei\Readyo\WebradioBundle\Entity\Image $pictureSmall
     * @return Show
     */
    public function setPictureSmall(\Efrei\Readyo\WebradioBundle\Entity\Image $pictureSmall = null)
    {
        $this->pictureSmall = $pictureSmall;

        return $this;
    }

    /**
     * Get pictureSmall
     *
     * @return \Efrei\Readyo\WebradioBundle\Entity\Image 
     */
    public function getPictureSmall()
    {
        return $this->pictureSmall;
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
}
