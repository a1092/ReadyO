<?php

namespace Efrei\Readyo\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AuthToken
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Efrei\Readyo\UserBundle\Entity\AuthTokenRepository")
 */
class AuthToken
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
     * @ORM\Column(name="grantedAt", type="datetime")
     */
    private $grantedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiredAt", type="datetime")
     */
    private $expiredAt;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=200)
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="plateform", type="string", length=255)
     */
    private $plateform;


    /**
     * @var boolean
     *
     * @ORM\Column(name="revoked", type="boolean")
     */
    private $isRevoked;

    /**
     * @var \Efrei\Readyo\UserBundle\Entity\User
     * 
     * @ORM\ManyToOne(targetEntity="Efrei\Readyo\UserBundle\Entity\User", inversedBy="tokens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;



    public function __construct()
    {
        $this->isRevoked = false;
        $this->grantedAt = new \Datetime();
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
     * Set grantedAt
     *
     * @param \DateTime $grantedAt
     * @return AuthToken
     */
    public function setGrantedAt($grantedAt)
    {
        $this->grantedAt = $grantedAt;

        return $this;
    }

    /**
     * Get grantedAt
     *
     * @return \DateTime 
     */
    public function getGrantedAt()
    {
        return $this->grantedAt;
    }

    /**
     * Set expiredAt
     *
     * @param \DateTime $expiredAt
     * @return AuthToken
     */
    public function setExpiredAt($expiredAt)
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    /**
     * Get expiredAt
     *
     * @return \DateTime 
     */
    public function getExpiredAt()
    {
        return $this->expiredAt;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return AuthToken
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set plateform
     *
     * @param string $plateform
     * @return AuthToken
     */
    public function setPlateform($plateform)
    {
        $this->plateform = $plateform;

        return $this;
    }

    /**
     * Get plateform
     *
     * @return string 
     */
    public function getPlateform()
    {
        return $this->plateform;
    }


    /**
     * Set isRevoked
     *
     * @param boolean $isRevoked
     * @return AuthToken
     */
    public function setIsRevoked($isRevoked)
    {
        $this->isRevoked = $isRevoked;

        return $this;
    }

    /**
     * Get isRevoked
     *
     * @return boolean 
     */
    public function getIsRevoked()
    {
        return $this->isRevoked;
    }

    /**
     * Set user
     *
     * @param \Efrei\Readyo\UserBundle\Entity\User $user
     * @return AuthToken
     */
    public function setUser(\Efrei\Readyo\UserBundle\Entity\User $user)
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
}
