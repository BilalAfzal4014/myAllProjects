<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * ActivitySchedule
 *
 * @ORM\Table(name="activity_schedule")
 * @ORM\Entity(repositoryClass="App\Repository\ActivityScheduleRepository")
 * @Assert\Callback(methods={"isPackageValid"})
 */
class ActivitySchedule extends BaseEntity
{

    public function __construct()
    {

        $this->scheduleDetail = new ArrayCollection();
        $this->multiPackage = new ArrayCollection();
    }


    private $helper = null;

    public function setHelper($helper)
    {
        $this->helper = $helper;
    }

    /**
     * @ORM\ManyToMany(targetEntity="Package")
     * @ORM\JoinTable(name="activity_schedule_package",
     *      joinColumns={@ORM\JoinColumn(onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(onDelete="CASCADE",nullable=false)}
     * )
     */

    private $multiPackage;

    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="activitySchedule")
     * @ORM\JoinColumn(name="instructor_id", referencedColumnName="id")
     */
    private $instructor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Activity", inversedBy="activitySchedule")
     * @ORM\JoinColumn(name="activity_id", referencedColumnName="id", onDelete="CASCADE")
     */
    public $activity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="activitySchedulefacility")
     * @ORM\JoinColumn(name="facility_id", referencedColumnName="id")
     */
    private $facility;


    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $pinaddress;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="datetime")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="datetime")
     */
    private $endDate;

    /**
     * @var int
     *
     * @ORM\Column(name="duration", type="smallint")
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="recurrence", type="string", length=255)
     */
    private $recurrence;


    /**
     * @ORM\Column(name="is_recommended", type="boolean" , options={"default":1})
     */
    private $isRecommended;

    /**
     * @ORM\Column(name="session_type", type="string", columnDefinition="enum('Morning', 'Evening', 'Afternoon')")
     */
    private $sessionType;

    /**
     * @ORM\Column(name="slots", type="integer", nullable=true)
     */
    private $slots;

    /**
     * @ORM\Column(name="is_location", type="boolean" , options={"default":0})
     */
    private $isLocation;

    /**
     * @ORM\Column(name="latitude", type="string", length=100, nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(name="longitude", type="string", length=100, nullable=true)
     */
    private $longitude;

    /**
     * @ORM\Column(name="allow_corepass", type="boolean" , options={"default":0})
     */
    private $allowCorepass;

    /**
     * @ORM\Column(name="is_free", type="boolean", options={"default":0})
     */
    private $isFree;
    /**
     * @ORM\OneToMany(targetEntity="ScheduleDetail", mappedBy="schedule")
     */
    private $scheduleDetail;

    /**
     * @return mixed
     */
    public function getSessionType()
    {
        return $this->sessionType;
    }

    /**
     * @param mixed $sessionType
     */
    public function setSessionType($sessionType)
    {
        $this->sessionType = $sessionType;
    }

    /**
     * @return mixed
     */
    public function getSlots()
    {
        return $this->slots;
    }

    /**
     * @param mixed $slots
     */
    public function setSlots($slots)
    {
        $this->slots = $slots;
    }

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
     * @return ActivitySchedule
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
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return ActivitySchedule
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return ActivitySchedule
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
     * @return ActivitySchedule
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
     * Set package
     *
     * @param Package $package
     *
     * @return ActivitySchedule
     */
    public function setPackage(Package $package = null)
    {
        $this->package = $package;

        return $this;
    }

    /**
     * Get package
     *
     * @return package
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * Set instructor
     *
     * @param User $instructor
     *
     * @return ActivitySchedule
     */
    public function setInstructor(User $instructor = null)
    {
        $this->instructor = $instructor;

        return $this;
    }

    /**
     * Get instructor
     *
     * @return User
     */
    public function getInstructor()
    {
        return $this->instructor;
    }

    /**
     * Set activity
     *
     * @param Activity $activity
     *
     * @return ActivitySchedule
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

    /**
     * Set facility
     *
     * @param User $facility
     *
     * @return ActivitySchedule
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

    public function __toString()
    {
        if ($this->activity) {
            if ($this->activity->getName()) {
                return $this->activity->getName();
            } else {
                return "Activity Schedule";
            }
        } else {
            return "Activity Schedule";
        }

    }



    public function getImageName()
    {
        if ($this->facility) {
            return $this->facility->getCompanyLogo();
        } else {
            return '';
        }
    }

    /**
     * Add multiPackage
     *
     * @param Package $multiPackage
     *
     * @return ActivitySchedule
     */
    public function addMultiPackage(Package $multiPackage)
    {
        $this->multiPackage[] = $multiPackage;

        return $this;
    }

    /**
     * Remove multiPackage
     *
     * @param Package $multiPackage
     */
    public function removeMultiPackage(Package $multiPackage)
    {
        $this->multiPackage->removeElement($multiPackage);
    }

    /**
     * Get multiPackage
     *
     * @return Collection
     */
    public function getMultiPackage()
    {
            return $this->multiPackage;
    }





    public function isPackageValid(\Symfony\Component\Validator\Context\ExecutionContext $context)
    {
        if( $this->getIsFree() != 1){
            $packages = $this->getMultiPackage();
            $packageArray = array();
            foreach($packages as $package){
                $packageArray[] =  $package->getName();
            }
            if(empty($packageArray)) {
                $context->addViolation('Package is required!', array(), null);
            }
        }
        

        /*
        $packages = $this->getMultiPackage();
        if (count($packages) == 0) {
            $context->addViolation('Package is required!', array(), null);
        }
        */

        $activity = $this->getActivity();

        if (!$activity) {
            $context->addViolation('Activity is required!', array(), null);

        }

        if ($this->getStartDate() && $this->getEndDate()) {

            $startDate = strtotime($this->getStartDate()->format("Y-m-d"));
            $endDate = strtotime($this->getEndDate()->format("Y-m-d"));
            if ($endDate < $startDate) {

                $context->addViolation('End Date must be greater than start date !', array(), null);
            }
        }


//        $instructor = $this->getInstructor();
//        if (!($instructor)) {
//            $context->addViolation('Instructor is required!', array(), null);
//        }

    }

    /**
     * Set isLocation
     *
     * @param boolean $isLocation
     *
     * @return ActivitySchedule
     */
    public function setIsLocation($isLocation)
    {
        $this->isLocation = $isLocation;

        return $this;
    }

    /**
     * Get isLocation
     *
     * @return boolean
     */
    public function getIsLocation()
    {
        return $this->isLocation;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return ActivitySchedule
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
     * @return ActivitySchedule
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
    
    public function getUsedSlots()
    {
        return $this->helper->getUsedSlots($this->getId());               
    }

    public function canEdit()
    {
        return $this->helper->canEdit($this->getId());
    }


    /**
     * Set allowCorepass
     *
     * @param boolean $allowCorepass
     *
     * @return ActivitySchedule
     */
    public function setAllowCorepass($allowCorepass)
    {
        $this->allowCorepass = $allowCorepass;

        return $this;
    }

    /**
     * Get allowCorepass
     *
     * @return boolean
     */
    public function getAllowCorepass()
    {
        return $this->allowCorepass;
    }


    /**
     * Set isFree
     *
     * @param boolean $isFree
     *
     * @return ActivitySchedule
     */
    public function setIsFree($isFree)
    {
        $this->isFree = $isFree;

        return $this;
    }

    /**
     * Get isFree
     *
     * @return boolean
     */
    public function getIsFree()
    {
        return $this->isFree;
    }

    /**
     * Add scheduleDetail
     *
     * @param \App\Entity\ScheduleDetail $scheduleDetail
     *
     * @return ActivitySchedule
     */
    public function addScheduleDetail(\App\Entity\ScheduleDetail $scheduleDetail)
    {
        $this->scheduleDetail[] = $scheduleDetail;

        return $this;
    }

    /**
     * Remove scheduleDetail
     *
     * @param \App\Entity\ScheduleDetail $scheduleDetail
     */
    public function removeScheduleDetail(\App\Entity\ScheduleDetail $scheduleDetail)
    {
        $this->scheduleDetail->removeElement($scheduleDetail);
    }

    /**
     * Get scheduleDetail
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getScheduleDetail()
    {
        return $this->scheduleDetail;
    }

    /**
     * @return mixed
     */
    public function getPinaddress()
    {
        return $this->pinaddress;
    }

    /**
     * @param mixed $pinaddress
     */
    public function setPinaddress($pinaddress)
    {
        $this->pinaddress = $pinaddress;
    }
}
