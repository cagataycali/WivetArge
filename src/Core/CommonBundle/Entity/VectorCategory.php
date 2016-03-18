<?php

namespace Core\CommonBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * VectorCategory
 *
 * @ORM\Table(name="vector_categorys")
 * @ORM\Entity(repositoryClass="Core\CommonBundle\Repository\VectorCategoryRepository")
 */
class VectorCategory
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
     * @ORM\OneToMany(targetEntity="InputVector", mappedBy="vector_category")
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
     * @return VectorCategory
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
     * @return VectorCategory
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
     * Add inputVector
     *
     * @param \Core\CommonBundle\Entity\InputVector $inputVector
     *
     * @return VectorCategory
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
