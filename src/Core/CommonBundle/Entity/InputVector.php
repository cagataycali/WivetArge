<?php

namespace Core\CommonBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * InputVector
 *
 * @ORM\Table(name="input_vectors")
 * @ORM\Entity(repositoryClass="Core\CommonBundle\Repository\InputVectorRepository")
 */
class InputVector
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
     * @ORM\OneToMany(targetEntity="TestCase", mappedBy="input_vector")
     */
    private $test_cases;

    /**
     * InputVector constructor.
     * @ORM\ManyToOne(targetEntity="VectorCategory", inversedBy="input_vectors")
     * @ORM\JoinColumn(name="vector_category_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $vector_category;

    /**
     * InputVector constructor.
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="input_vectors")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $event;

    /**
     * InputVector constructor.
     * @ORM\ManyToOne(targetEntity="HtmlElement", inversedBy="input_vectors")
     * @ORM\JoinColumn(name="html_element_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $html_element;

    public function __construct()
    {
        $this->test_cases = new ArrayCollection();
    }



    /**
     * Set id
     *
     * @param integer $id
     *
     * @return InputVector
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
     * Add testCase
     *
     * @param \Core\CommonBundle\Entity\TestCase $testCase
     *
     * @return InputVector
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

    /**
     * Set vectorCategory
     *
     * @param \Core\CommonBundle\Entity\VectorCategory $vectorCategory
     *
     * @return InputVector
     */
    public function setVectorCategory(\Core\CommonBundle\Entity\VectorCategory $vectorCategory = null)
    {
        $this->vector_category = $vectorCategory;

        return $this;
    }

    /**
     * Get vectorCategory
     *
     * @return \Core\CommonBundle\Entity\VectorCategory
     */
    public function getVectorCategory()
    {
        return $this->vector_category;
    }

    /**
     * Set event
     *
     * @param \Core\CommonBundle\Entity\Event $event
     *
     * @return InputVector
     */
    public function setEvent(\Core\CommonBundle\Entity\Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \Core\CommonBundle\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set htmlElement
     *
     * @param \Core\CommonBundle\Entity\HtmlElement $htmlElement
     *
     * @return InputVector
     */
    public function setHtmlElement(\Core\CommonBundle\Entity\HtmlElement $htmlElement = null)
    {
        $this->html_element = $htmlElement;

        return $this;
    }

    /**
     * Get htmlElement
     *
     * @return \Core\CommonBundle\Entity\HtmlElement
     */
    public function getHtmlElement()
    {
        return $this->html_element;
    }
}
