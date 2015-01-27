<?php

namespace Efrei\Readyo\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use FOS\UserBundle\Model\User as BaseUser;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Since;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Efrei\Readyo\UserBundle\Entity\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @ExclusionPolicy("all") 
 *
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    protected $username;
    protected $email;


    
    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=200, nullable=true)
     *
     * @Expose
     * @Groups({"list", "details"})
     * @Since("1.0")
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=200, nullable=true)
     *
     * @Expose
     * @Groups({"list", "details"})
     * @Since("1.0")
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=20, nullable=true)
     *
     * @Expose
     * @Groups({"list", "details"})
     * @Since("1.0")
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="birthdate", type="date", nullable=true) 
     *
     * @Expose
     * @Groups({"list", "details"})
     * @Since("1.0")
     */
    private $birthdate;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picturePath;

    /**
     * @Assert\File(maxSize="2048k")
     * @Assert\Image(mimeTypesMessage="Please upload a valid image.")
     */
    public $pictureFile;


    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true) 
     *
     * @Expose
     * @Groups({"list", "details"})
     * @Since("1.0")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modifiedTime", type="datetime", nullable=true)
     */
    private $modifiedTime;



    /**
     * @var \Efrei\Readyo\WebradioBundle\Entity\PodcastComment
     * 
     * @ORM\OneToMany(targetEntity="Efrei\Readyo\WebradioBundle\Entity\PodcastComment", mappedBy="user")
     */
    private $comments;

    /**
     * @var \Efrei\Readyo\UserBundle\Entity\AuthToken
     * 
     * @ORM\OneToMany(targetEntity="Efrei\Readyo\UserBundle\Entity\AuthToken", mappedBy="user")
     */
    private $tokens;


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
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     * @return User
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime 
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
        parent::__construct();
    }


    /**
     * Add comments
     *
     * @param \Efrei\Readyo\WebradioBundle\Entity\PodcastComment $comments
     * @return User
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
     * Add tokens
     *
     * @param \Efrei\Readyo\UserBundle\Entity\AuthToken $tokens
     * @return User
     */
    public function addToken(\Efrei\Readyo\UserBundle\Entity\AuthToken $tokens)
    {
        $this->tokens[] = $tokens;

        return $this;
    }

    /**
     * Remove tokens
     *
     * @param \Efrei\Readyo\UserBundle\Entity\AuthToken $tokens
     */
    public function removeToken(\Efrei\Readyo\UserBundle\Entity\AuthToken $tokens)
    {
        $this->tokens->removeElement($tokens);
    }

    /**
     * Get tokens
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTokens()
    {
        return $this->tokens;
    }





    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {

        if ($this->pictureFile !== null) {
            $this->removePicture($this->getPictureAbsolutePath());
            $this->picturePath = sha1(uniqid(mt_rand(), true)).'.'.$this->pictureFile->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {

        if ($this->pictureFile !== null) {
            $this->pictureFile->move($this->getUploadRootDir(), $this->picturePath);
            unset($this->pictureFile);
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        $this->removePicture($this->getPictureAbsolutePath());
    }


    private function removePicture($file) {

        if(!empty($file))
            unlink($file);
    }


    public function getPictureAbsolutePath()
    {
        return null === $this->picturePath ? null : $this->getUploadRootDir().'/'.$this->picturePath;
    }

    /**
     * @VirtualProperty
     * @Type("string")
     * @SerializedName("picture")
     * @Groups({"list", "details"})
     */
    public function getPictureWebPath()
    {
        return null === $this->picturePath ? null : $this->getUploadDir().'/'.$this->picturePath;
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
        return 'data/users/pictures';
    }

    /**
     * Set picturePath
     *
     * @param string $picturePath
     * @return User
     */
    public function setPicturePath($picturePath)
    {
        $this->picturePath = $picturePath;

        return $this;
    }

    /**
     * Get picturePath
     *
     * @return string 
     */
    public function getPicturePath()
    {
        return $this->picturePath;
    }

    /**
     * Set modifiedTime
     *
     * @param \DateTime $modifiedTime
     * @return User
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
