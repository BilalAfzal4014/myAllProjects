<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ManualCheckinHistory
 *
 * @ORM\Table(name="manual_checkin_history")
 * @ORM\Entity(repositoryClass="App\Repository\ManualCheckinHistoryRepository")
 */
class ManualCheckinHistory extends  BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Activity",inversedBy="")
     */
    private $activity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User",inversedBy="")
     */
    private $member;

    /**
     * @ORM\Column(type="datetime",name="time")
     */
    private $time;

    /**
     * @return mixed
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * @param mixed $activity
     */
    public function setActivity($activity)
    {
        $this->activity = $activity;
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
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }


}

