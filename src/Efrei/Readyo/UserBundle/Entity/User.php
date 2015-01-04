<?php

namespace Efrei\Readyo\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use FOS\UserBundle\Model\User as BaseUser;

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
     * @var array
     *
     * @ORM\OneToOne(targetEntity="Efrei\Readyo\UserBundle\Entity\UserPicture", cascade={"persist"})
     *
     */
    private $picture;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="validateAt", type="datetime", nullable=true)
     * 
     * @Expose
     * @Groups({"list", "details"})
     * @Since("1.0")
     */
    private $validateAt;

    
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
     * @ORM\Column(name="lastConnection", type="datetime", nullable=true)
     */
    private $lastConnectionAt;


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
     * @VirtualProperty
     * @Type("string")
     * @SerializedName("picture")
     * @Groups({"details"})
     * @Since("1.0")
     */
    public function picture() {
        return $this->picture->getWebPath();
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
     * Set validateAt
     *
     * @param \DateTime $validateAt
     * @return User
     */
    public function setValidateAt($validateAt)
    {
        $this->validateAt = $validateAt;

        return $this;
    }

    /**
     * Get validateAt
     *
     * @return \DateTime 
     */
    public function getValidateAt()
    {
        return $this->validateAt;
    }

    /**
     * Set picture
     *
     * @param \Efrei\Readyo\UserBundle\Entity\UserPicture $picture
     * @return User
     */
    public function setPicture(\Efrei\Readyo\UserBundle\Entity\UserPicture $picture = null)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return \Efrei\Readyo\UserBundle\Entity\UserPicture 
     */
    public function getPicture()
    {
        return $this->picture;
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
     * Set lastConnectionAt
     *
     * @param \DateTime $lastConnectionAt
     * @return User
     */
    public function setLastConnectionAt($lastConnectionAt)
    {
        $this->lastConnectionAt = $lastConnectionAt;

        return $this;
    }

    /**
     * Get lastConnectionAt
     *
     * @return \DateTime 
     */
    public function getLastConnectionAt()
    {
        return $this->lastConnectionAt;
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
}
