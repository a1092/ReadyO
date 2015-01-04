<?php

namespace Efrei\Readyo\PalladiumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Output
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Efrei\Readyo\PalladiumBundle\Entity\OutputRepository")
 */
class Output
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

   /**
    * @ORM\ManyToMany(targetEntity="Efrei\Readyo\WebradioBundle\Entity\Schedule", mappedBy="outputs")
    */
    private $schedules;

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
     * Set name
     *
     * @param string $name
     * @return Output
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Output
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
     * Constructor
     */
    public function __construct()
    {
        $this->schedules = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add schedules
     *
     * @param \Efrei\Readyo\WebradioBundle\Entity\Schedule $schedules
     * @return Output
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

    public function __toString() {
        return $this->name;
    }
}
