<?php

namespace Core\CommonBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TestCaseMethod
 *
 * @ORM\Table(name="test_case_methods")
 * @ORM\Entity(repositoryClass="Core\CommonBundle\Repository\TestCaseMethodRepository")
 */
class TestCaseMethod
{
    /**
     * @var int
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
     * @ORM\OneToMany(targetEntity="TestCase", mappedBy="method")
     */
    private $test_cases;

    public function __construct()
    {
        $this->test_cases = new ArrayCollection();
    }


    /**
     * Set id
     *
     * @param integer $id
     *
     * @return TestCaseMethod
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
     * @return TestCaseMethod
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
     * Add testCase
     *
     * @param \Core\CommonBundle\Entity\TestCase $testCase
     *
     * @return TestCaseMethod
     */
    public function addTestCase(\Core\CommonBundle\Entity\TestCase $testCase)
    {
        $this->test_cases[] = $testCase;

        return $this;
    }

    /**
     * Remove testCase
     *
     * @param \Core\CommonBundle\Entity\TestCase $testCase
     */
    public function removeTestCase(\Core\CommonBundle\Entity\TestCase $testCase)
    {
        $this->test_cases->removeElement($testCase);
    }

    /**
     * Get testCases
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTestCases()
    {
        return $this->test_cases;
    }
}
