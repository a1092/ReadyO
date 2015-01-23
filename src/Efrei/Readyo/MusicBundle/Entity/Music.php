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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->playlist = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set trackName
     *
     * @param string $trackName
     * @return Music
     */
    public function setTrackName($trackName)
    {
        $this->trackName = $trackName;

        return $this;
    }

    /**
     * Get trackName
     *
     * @return string 
     */
    public function getTrackName()
    {
        return $this->trackName;
    }

    /**
     * Set trackSpotify
     *
     * @param string $trackSpotify
     * @return Music
     */
    public function setTrackSpotify($trackSpotify)
    {
        $this->trackSpotify = $trackSpotify;

        return $this;
    }

    /**
     * Get trackSpotify
     *
     * @return string 
     */
    public function getTrackSpotify()
    {
        return $this->trackSpotify;
    }

    /**
     * Set artistName
     *
     * @param string $artistName
     * @return Music
     */
    public function setArtistName($artistName)
    {
        $this->artistName = $artistName;

        return $this;
    }

    /**
     * Get artistName
     *
     * @return string 
     */
    public function getArtistName()
    {
        return $this->artistName;
    }

    /**
     * Set artistSpotify
     *
     * @param string $artistSpotify
     * @return Music
     */
    public function setArtistSpotify($artistSpotify)
    {
        $this->artistSpotify = $artistSpotify;

        return $this;
    }

    /**
     * Get artistSpotify
     *
     * @return string 
     */
    public function getArtistSpotify()
    {
        return $this->artistSpotify;
    }

    /**
     * Set albumName
     *
     * @param string $albumName
     * @return Music
     */
    public function setAlbumName($albumName)
    {
        $this->albumName = $albumName;

        return $this;
    }

    /**
     * Get albumName
     *
     * @return string 
     */
    public function getAlbumName()
    {
        return $this->albumName;
    }

    /**
     * Set albumSpotify
     *
     * @param string $albumSpotify
     * @return Music
     */
    public function setAlbumSpotify($albumSpotify)
    {
        $this->albumSpotify = $albumSpotify;

        return $this;
    }

    /**
     * Get albumSpotify
     *
     * @return string 
     */
    public function getAlbumSpotify()
    {
        return $this->albumSpotify;
    }

    /**
     * Add playlist
     *
     * @param \Efrei\Readyo\MusicBundle\Entity\MusicPlayed $playlist
     * @return Music
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
}
