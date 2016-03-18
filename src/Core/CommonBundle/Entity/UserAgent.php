<?php

namespace Core\CommonBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * UserAgent
 *
 * @ORM\Table(name="user_agents")
 * @ORM\Entity(repositoryClass="Core\CommonBundle\Repository\UserAgentRepository")
 */
class UserAgent
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
     * @ORM\OneToMany(targetEntity="Record", mappedBy="user_agent")
     */
    private $records;

    public function __construct()
    {
        $this->records = new ArrayCollection();
    }


    /**
     * Set id
     *
     * @param integer $id
     *
     * @return UserAgent
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
     * Add record
     *
     * @param \Core\CommonBundle\Entity\Record $record
     *
     * @return UserAgent
     */
    public function addRecord(\Core\CommonBundle\Entity\Record $record)
    {
        $this->records[] = $record;

        return $this;
    }

    /**
     * Remove record
     *
     * @param \Core\CommonBundle\Entity\Record $record
     */
    public function removeRecord(\Core\CommonBundle\Entity\Record $record)
    {
        $this->records->removeElement($record);
    }

    /**
     * Get records
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecords()
    {
        return $this->records;
    }
}
