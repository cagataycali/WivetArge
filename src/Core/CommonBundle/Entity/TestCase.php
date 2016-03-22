<?php

namespace Core\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TestCase
 *
 * @ORM\Table(name="test_cases")
 * @ORM\Entity(repositoryClass="Core\CommonBundle\Repository\TestCaseRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class TestCase
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="key", type="string", length=255)
     */
    private $key;

    /**
     * @ORM\ManyToOne(targetEntity="Record", inversedBy="test_cases")
     * @ORM\JoinColumn(name="record_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $record;

    /**
     * @ORM\ManyToOne(targetEntity="TestCaseMethod", inversedBy="test_cases")
     * @ORM\JoinColumn(name="method_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $method;

    /**
     * @ORM\ManyToOne(targetEntity="TestCaseDescription", inversedBy="test_cases")
     * @ORM\JoinColumn(name="test_case_description_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $test_case_description;

    /**
     * @ORM\ManyToOne(targetEntity="InputVector", inversedBy="test_cases")
     * @ORM\JoinColumn(name="input_vector_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $input_vector;

    /**
     * @var int
     *
     * @ORM\Column(name="weight", type="integer", nullable=true)
     */
    private $weight;

    /**
     * @var int
     *
     * @ORM\Column(name="click_count", type="integer", nullable=true)
     */
    private $clickCount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="firstClickedAt", type="datetime", nullable=true)
     */
    private $firstClickedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastClickedAt", type="datetime", nullable=true)
     */
    private $lastClickedAt;

    /**
     * Now we tell doctrine that before we persist or update we call the updatedTimestamps() function.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {

        if($this->getLastClickedAt() == null)
        {
            $this->setLastClickedAt(new \DateTime(date('d-m-Y H:i:s')));
        }
        if($this->getClickCount() == null)
        {
            $this->setClickCount(0);
        }
    }

    public function __toString()
    {
        return (string) $this->getClickCount();
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
     * Set key
     *
     * @param string $key
     *
     * @return TestCase
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     *
     * @return TestCase
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
     * Set clickCount
     *
     * @param integer $clickCount
     *
     * @return TestCase
     */
    public function setClickCount($clickCount)
    {
        $this->clickCount = $clickCount;

        return $this;
    }

    /**
     * Get clickCount
     *
     * @return integer
     */
    public function getClickCount()
    {
        return $this->clickCount;
    }

    /**
     * Set firstClickedAt
     *
     * @param \DateTime $firstClickedAt
     *
     * @return TestCase
     */
    public function setFirstClickedAt($firstClickedAt)
    {
        $this->firstClickedAt = $firstClickedAt;

        return $this;
    }

    /**
     * Get firstClickedAt
     *
     * @return \DateTime
     */
    public function getFirstClickedAt()
    {
        return $this->firstClickedAt;
    }

    /**
     * Set lastClickedAt
     *
     * @param \DateTime $lastClickedAt
     *
     * @return TestCase
     */
    public function setLastClickedAt($lastClickedAt)
    {
        $this->lastClickedAt = $lastClickedAt;

        return $this;
    }

    /**
     * Get lastClickedAt
     *
     * @return \DateTime
     */
    public function getLastClickedAt()
    {
        return $this->lastClickedAt;
    }

    /**
     * Set record
     *
     * @param \Core\CommonBundle\Entity\Record $record
     *
     * @return TestCase
     */
    public function setRecord(\Core\CommonBundle\Entity\Record $record = null)
    {
        $this->record = $record;

        return $this;
    }

    /**
     * Get record
     *
     * @return \Core\CommonBundle\Entity\Record
     */
    public function getRecord()
    {
        return $this->record;
    }

    /**
     * Set method
     *
     * @param \Core\CommonBundle\Entity\TestCaseMethod $method
     *
     * @return TestCase
     */
    public function setMethod(\Core\CommonBundle\Entity\TestCaseMethod $method = null)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get method
     *
     * @return \Core\CommonBundle\Entity\TestCaseMethod
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set testCaseDescription
     *
     * @param \Core\CommonBundle\Entity\TestCaseDescription $testCaseDescription
     *
     * @return TestCase
     */
    public function setTestCaseDescription(\Core\CommonBundle\Entity\TestCaseDescription $testCaseDescription = null)
    {
        $this->test_case_description = $testCaseDescription;

        return $this;
    }

    /**
     * Get testCaseDescription
     *
     * @return \Core\CommonBundle\Entity\TestCaseDescription
     */
    public function getTestCaseDescription()
    {
        return $this->test_case_description;
    }

    /**
     * Set inputVector
     *
     * @param \Core\CommonBundle\Entity\InputVector $inputVector
     *
     * @return TestCase
     */
    public function setInputVector(\Core\CommonBundle\Entity\InputVector $inputVector = null)
    {
        $this->input_vector = $inputVector;

        return $this;
    }

    /**
     * Get inputVector
     *
     * @return \Core\CommonBundle\Entity\InputVector
     */
    public function getInputVector()
    {
        return $this->input_vector;
    }
}
