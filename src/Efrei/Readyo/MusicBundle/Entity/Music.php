<?php

namespace Efrei\Readyo\MusicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Music
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Efrei\Readyo\MusicBundle\Entity\MusicRepository")
 */
class Music
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
     * @var string
     *
     * @ORM\Column(name="trackName", type="string", length=255)
     */
    private $trackName;

    /**
     * @var string
     *
     * @ORM\Column(name="trackSpotify", type="string", length=150)
     */
    private $trackSpotify;

    /**
     * @var string
     *
     * @ORM\Column(name="artistName", type="string", length=255)
     */
    private $artistName;

    /**
     * @var string
     *
     * @ORM\Column(name="artistSpotify", type="string", length=255)
     */
    private $artistSpotify;

    /**
     * @var string
     *
     * @ORM\Column(name="albumName", type="string", length=255)
     */
    private $albumName;

    /**
     * @var string
     *
     * @ORM\Column(name="albumSpotify", type="string", length=255)
     */
    private $albumSpotify;


    /**
     * @var \Efrei\Readyo\MusicBundle\Entity\MusicPlayed
     * 
     * @ORM\OneToMany(targetEntity="Efrei\Readyo\MusicBundle\Entity\MusicPlayed", mappedBy="music")
     */
    private $playlist;


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
