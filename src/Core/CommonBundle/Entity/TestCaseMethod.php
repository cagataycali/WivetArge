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
     * @ORM\OneToMany(targetEntity="TestCase", mappedBy="method")
     */
    private $test_cases;

    public function __construct()
    {
        $this->test_cases = new ArrayCollection();
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
