<?php

namespace Core\CommonBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Record
 *
 * @ORM\Table(name="records")
 * @ORM\Entity(repositoryClass="Core\CommonBundle\Repository\RecordRepository")
 */
class Record
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
     * @ORM\Column(name="php_session_id", type="string", length=255)
     */
    private $phpSessionId;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string", length=255)
     */
    private $ipAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="record_key", type="string", length=255, unique=true)
     */
    private $recordKey;

    /**
     * @ORM\ManyToOne(targetEntity="UserAgent", inversedBy="records")
     * @ORM\JoinColumn(name="user_agent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user_agent;

    /**
     * @ORM\OneToMany(targetEntity="TestCase", mappedBy="record")
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
     * @return Record
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
     * Set phpSessionId
     *
     * @param string $phpSessionId
     *
     * @return Record
     */
    public function setPhpSessionId($phpSessionId)
    {
        $this->phpSessionId = $phpSessionId;

        return $this;
    }

    /**
     * Get phpSessionId
     *
     * @return string
     */
    public function getPhpSessionId()
    {
        return $this->phpSessionId;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     *
     * @return Record
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set recordKey
     *
     * @param string $recordKey
     *
     * @return Record
     */
    public function setRecordKey($recordKey)
    {
        $this->recordKey = $recordKey;

        return $this;
    }

    /**
     * Get recordKey
     *
     * @return string
     */
    public function getRecordKey()
    {
        return $this->recordKey;
    }

    /**
     * Set userAgent
     *
     * @param \Core\CommonBundle\Entity\UserAgent $userAgent
     *
     * @return Record
     */
    public function setUserAgent(\Core\CommonBundle\Entity\UserAgent $userAgent = null)
    {
        $this->user_agent = $userAgent;

        return $this;
    }

    /**
     * Get userAgent
     *
     * @return \Core\CommonBundle\Entity\UserAgent
     */
    public function getUserAgent()
    {
        return $this->user_agent;
    }

    /**
     * Add testCase
     *
     * @param \Core\CommonBundle\Entity\TestCase $testCase
     *
     * @return Record
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
