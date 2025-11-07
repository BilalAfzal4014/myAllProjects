<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ScheduleDetail
 *
 * @ORM\Table(name="schedule_detail")
 * @ORM\Entity(repositoryClass="App\Repository\ScheduleDetailRepository")
 */
class ScheduleDetail extends BaseEntity
{
    public function __construct()
    {
        $this->member_schedule_activity = new ArrayCollection();
    }

    private $scheduleDetailHelper = null;

    public function sdHelper($helper)
    {

        $this->scheduleDetailHelper = $helper;
    }


    /**
     *
     * @ORM\ManyToOne(targetEntity="ActivitySchedule", inversedBy="scheduleDetail")
     * @ORM\JoinColumn(name="schedule_id", referencedColumnName="id")
     */
    public $schedule;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="schedule_date", type="datetime")
     */
    private $scheduleDate;

    /**
     * @var int
     *
     * @ORM\Column(name="duration", type="smallint")
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="recurrence", type="string", columnDefinition="enum('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
     */
    private $recurrence;

    /**
     * @var string
     *
     * @ORM\Column(name="session_type", type="string", columnDefinition="enum('Morning', 'Evening', 'Afternoon')")
     */
    private $sessionType;

    /**
     * @var int
     *
     * @ORM\Column(name="slots", type="integer" , nullable=true)
     */
    private $slots;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_cancel", type="boolean", options={"default":0})
     */
    private $isCancel;

    /**
     * @ORM\Column(name="latitude", type="string", length=100, nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(name="longitude", type="string", length=100, nullable=true)
     */
    private $longitude;

    /**
     * @ORM\OneToMany(targetEntity="MemberScheduleActivity", mappedBy="scheduleDetail")
     */
    private $member_schedule_activity;

    /**
     * Set scheduleDate
     *
     * @param \DateTime $scheduleDate
     *
     * @return ScheduleDetail
     */
    public function setScheduleDate($scheduleDate)
    {
        $this->scheduleDate = $scheduleDate;

        return $this;
    }

    /**
     * Get scheduleDate
     *
     * @return \DateTime
     */
    public function getScheduleDate()
    {
        return $this->scheduleDate;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return ScheduleDetail
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set recurrence
     *
     * @param string $recurrence
     *
     * @return ScheduleDetail
     */
    public function setRecurrence($recurrence)
    {
        $this->recurrence = $recurrence;

        return $this;
    }

    /**
     * Get recurrence
     *
     * @return string
     */
    public function getRecurrence()
    {
        return $this->recurrence;
    }

    /**
     * Set sessionType
     *
     * @param string $sessionType
     *
     * @return ScheduleDetail
     */
    public function setSessionType($sessionType)
    {
        $this->sessionType = $sessionType;

        return $this;
    }

    /**
     * Get sessionType
     *
     * @return string
     */
    public function getSessionType()
    {
        return $this->sessionType;
    }

    /**
     * Set slots
     *
     * @param integer $slots
     *
     * @return ScheduleDetail
     */
    public function setSlots($slots)
    {
        $this->slots = $slots;

        return $this;
    }

    /**
     * Get slots
     *
     * @return int
     */
    public function getSlots()
    {
        return $this->slots;
    }

    /**
     * Set isCancel
     *
     * @param boolean $isCancel
     *
     * @return ScheduleDetail
     */
    public function setIsCancel($isCancel)
    {
        $this->isCancel = $isCancel;

        return $this;
    }

    /**
     * Get isCancel
     *
     * @return bool
     */
    public function getIsCancel()
    {
        return $this->isCancel;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return ScheduleDetail
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return ScheduleDetail
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set schedule
     *
     * @param ActivitySchedule $schedule
     *
     * @return ScheduleDetail
     */
    public function setSchedule(ActivitySchedule $schedule = null)
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * Get schedule
     *
     * @return ActivitySchedule
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * Add memberScheduleActivity
     *
     * @param MemberScheduleActivity $memberScheduleActivity
     *
     * @return ScheduleDetail
     */
    public function addMemberScheduleActivity(MemberScheduleActivity $memberScheduleActivity)
    {
        $this->member_schedule_activity[] = $memberScheduleActivity;

        return $this;
    }

    /**
     * Remove memberScheduleActivity
     *
     * @param MemberScheduleActivity $memberScheduleActivity
     */
    public function removeMemberScheduleActivity(MemberScheduleActivity $memberScheduleActivity)
    {
        $this->member_schedule_activity->removeElement($memberScheduleActivity);
    }

    /**
     * Get memberScheduleActivity
     *
     * @return Collection
     */
    public function getMemberScheduleActivity()
    {
        return $this->member_schedule_activity;
    }

    public function getBooked()
    {

        return $this->scheduleDetailHelper->getScheduleDetailBooked($this->getId());
    }

    public function getAllBookedSlots($id)
    {
        return $this->scheduleDetailHelper->getAllBookedSlots($id);
    }

    public function getBookedSlots($id)
    {
        return $this->scheduleDetailHelper->getBookedSlots($id);
    }

    public function getScheduleDateForExport(){
        return ($this->scheduleDate instanceof \DateTime) ? $this->scheduleDate->format("d/m/y") : "N/A";
    }
    public function getScheduleTimeForExport(){
        return ($this->scheduleDate instanceof \DateTime) ? $this->scheduleDate->format("h:i A") : "N/A";
    }
}
