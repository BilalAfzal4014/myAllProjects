<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Query\Expr\Base;
use Sonata\MediaBundle\Validator\Constraints\ValidMediaFormat;

/**
 * FacilityActivity
 *
 * @ORM\Table(name="facility_activity")
 * @ORM\Entity(repositoryClass="App\Repository\FacilityActivityRepository")
 */
class FacilityActivity extends BaseEntity
{


    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="facilityActivity")
     * @ORM\JoinColumn(name="facility_id", referencedColumnName="id")
     */
    private $facility;

    /**
     * @ORM\ManyToOne(targetEntity="Activity", inversedBy="facilityActivity")
     * @ORM\JoinColumn(name="activity_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $activity;


    /**
     * @ORM\Column(name="is_recommended", type="boolean" , options={"default":0})
     */
    private $isRecommended = false;

    /**
     * @return mixed
     */
    public function getIsRecommended()
    {
        return $this->isRecommended;
    }

    /**
     * @param mixed $isRecommended
     */
    public function setIsRecommended($isRecommended)
    {
        $this->isRecommended = $isRecommended;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return FacilityActivity
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set facility
     *
     * @param User $facility
     *
     * @return FacilityActivity
     */
    public function setFacility(User $facility = null)
    {
        $this->facility = $facility;

        return $this;
    }

    /**
     * Get facility
     *
     * @return User
     */
    public function getFacility()
    {
        return $this->facility;
    }

    /**
     * Set activity
     *
     * @param Activity $activity
     *
     * @return FacilityActivity
     */
    public function setActivity(Activity $activity = null)
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * Get activity
     *
     * @return Activity
     */
    public function getActivity()
    {
        return $this->activity;
    }

    public function getImageName(){
        if($this->facility){
            return $this->facility->getCompanyLogo();

        }else{
            return false;
        }
    }
}
