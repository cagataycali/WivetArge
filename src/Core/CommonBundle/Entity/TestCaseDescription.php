<?php

namespace Core\CommonBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TestCaseDescription
 *
 * @ORM\Table(name="test_case_descriptions")
 * @ORM\Entity(repositoryClass="Core\CommonBundle\Repository\TestCaseDescriptionRepository")
 */
class TestCaseDescription
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
     * @ORM\Column(name="content", type="string", length=255)
     */
    private $content;

    /**
     * @ORM\OneToMany(targetEntity="TestCase", mappedBy="test_case_description")
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
     * @return TestCaseDescription
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
     * Set content
     *
     * @param string $content
     *
     * @return TestCaseDescription
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Add testCase
     *
     * @param \Core\CommonBundle\Entity\TestCase $testCase
     *
     * @return TestCaseDescription
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
