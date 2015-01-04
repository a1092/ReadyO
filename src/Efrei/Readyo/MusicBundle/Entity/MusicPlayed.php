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
}
