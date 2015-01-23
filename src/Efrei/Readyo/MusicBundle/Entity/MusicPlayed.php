<?php

namespace Efrei\Readyo\MusicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MusicPlayed
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Efrei\Readyo\MusicBundle\Entity\MusicPlayedRepository")
 */
class MusicPlayed
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
     * @ORM\Column(name="playedAt", type="datetime")
     */
    private $playedAt;


    /**
     * @var \Efrei\Readyo\WebradioBundle\Entity\Schedule
     * 
     * @ORM\ManyToOne(targetEntity="Efrei\Readyo\WebradioBundle\Entity\Schedule", inversedBy="playlist")
     * @ORM\JoinColumn(nullable=false)
     */
    private $schedule;

    /**
     * @var \Efrei\Readyo\MusicBundle\Entity\Music
     * 
     * @ORM\ManyToOne(targetEntity="Efrei\Readyo\MusicBundle\Entity\Music", inversedBy="playlist")
     * @ORM\JoinColumn(nullable=false)
     */
    private $music;


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
     * Set playedAt
     *
     * @param \DateTime $playedAt
     * @return MusicPlayed
     */
    public function setPlayedAt($playedAt)
    {
        $this->playedAt = $playedAt;

        return $this;
    }

    /**
     * Get playedAt
     *
     * @return \DateTime 
     */
    public function getPlayedAt()
    {
        return $this->playedAt;
    }

    /**
     * Set schedule
     *
     * @param \Efrei\Readyo\WebradioBundle\Entity\Schedule $schedule
     * @return MusicPlayed
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
     * Set music
     *
     * @param \Efrei\Readyo\MusicBundle\Entity\Music $music
     * @return MusicPlayed
     */
    public function setMusic(\Efrei\Readyo\MusicBundle\Entity\Music $music)
    {
        $this->music = $music;

        return $this;
    }

    /**
     * Get music
     *
     * @return \Efrei\Readyo\MusicBundle\Entity\Music 
     */
    public function getMusic()
    {
        return $this->music;
    }
}
