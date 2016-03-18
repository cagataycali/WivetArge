<?php

namespace Core\CommonBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table(name="events")
 * @ORM\Entity(repositoryClass="Core\CommonBundle\Repository\EventRepository")
 */
class Event
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="weight", type="integer")
     */
    private $weight;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\OneToMany(targetEntity="InputVector", mappedBy="event")
     */
    private $input_vectors;

    public function __construct()
    {
        $this->input_vectors = new ArrayCollection();
    }


    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Event
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set name
     *
     * @param string $name
     *
     * @return Event
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
     * Set weight
     *
     * @param integer $weight
     *
     * @return Event
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return integer
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Event
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Add inputVector
     *
     * @param \Core\CommonBundle\Entity\InputVector $inputVector
     *
     * @return Event
     */
    public function addInputVector(\Core\CommonBundle\Entity\InputVector $inputVector)
    {
        $this->input_vectors[] = $inputVector;

        return $this;
    }

    /**
     * Remove inputVector
     *
     * @param \Core\CommonBundle\Entity\InputVector $inputVector
     */
    public function removeInputVector(\Core\CommonBundle\Entity\InputVector $inputVector)
    {
        $this->input_vectors->removeElement($inputVector);
    }

    /**
     * Get inputVectors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInputVectors()
    {
        return $this->input_vectors;
    }
}
