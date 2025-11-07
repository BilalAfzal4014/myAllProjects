<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FacilityCheckinHistory
 *
 * @ORM\Table(name="facility_checkin_history")
 * @ORM\Entity(repositoryClass="App\Repository\FacilityCheckinHistoryRepository")
 */
class FacilityCheckinHistory extends BaseEntity
{


    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User",inversedBy="nonPartnerFacility")
     */
    private $facility;

    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User",inversedBy="nonPartnerMember")
     */
    private $member;

    /**
     * @ORM\Column(name="checkin", type="integer", options={"default"=0})
     */
    private $checkIn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime")
     */
    private $time;

    /**
     * @return mixed
     */
    public function getFacility()
    {
        return $this->facility;
    }

    /**
     * @param mixed $facility
     */
    public function setFacility($facility)
    {
        $this->facility = $facility;
    }

    /**
     * @return mixed
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * @param mixed $member
     */
    public function setMember($member)
    {
        $this->member = $member;
    }



    /**
     * @return mixed
     */
    public function getCheckIn()
    {
        return $this->checkIn;
    }

    /**
     * @param mixed $checkIn
     */
    public function setCheckIn($checkIn)
    {
        $this->checkIn = $checkIn;
    }

    /**
     * @param mixed $checkIn
     */
    public function setIncrementCheckIn()
    {
        $this->checkIn = 1 + $this->checkIn  ;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param \DateTime $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

}

