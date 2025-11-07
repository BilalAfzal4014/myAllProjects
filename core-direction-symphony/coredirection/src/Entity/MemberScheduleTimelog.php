<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MemberScheduleTimelog
 *
 * @ORM\Table(name="member_schedule_timelog")
 * @ORM\Entity(repositoryClass="App\Repository\MemberScheduleTimelogRepository")
 */
class MemberScheduleTimelog
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
     * @ORM\ManyToOne(targetEntity="MemberScheduleActivity", inversedBy="memberScheduleTimelog")
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $book;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="checkInTime", type="datetime")
     */
    private $checkInTime;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set checkInTime
     *
     * @param \DateTime $checkInTime
     *
     * @return MemberScheduleTimelog
     */
    public function setCheckInTime($checkInTime)
    {
        $this->checkInTime = $checkInTime;

        return $this;
    }

    /**
     * Get checkInTime
     *
     * @return \DateTime
     */
    public function getCheckInTime()
    {
        return $this->checkInTime;
    }

    /**
     * Set book
     *
     * @param \App\Entity\MemberScheduleActivity $book
     *
     * @return MemberScheduleTimelog
     */
    public function setBook(\App\Entity\MemberScheduleActivity $book = null)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get book
     *
     * @return \App\Entity\MemberScheduleActivity
     */
    public function getBook()
    {
        return $this->book;
    }
}
