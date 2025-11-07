<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;

/**
 * MemberScheduleActivity
 *
 * @ORM\Table(name="member_schedule_activity")
 * @ORM\Entity(repositoryClass="App\Repository\MemberScheduleActivityRepository")
 */
class MemberScheduleActivity extends BaseEntity
{

    public function __construct()
    {

        $this->memberScheduleTimelog = new ArrayCollection();
    }

    public function __toString()
    {
        return "Member Schedule Activity";
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="memberSchedule")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
    private $member;

    /**
     * @ORM\ManyToOne(targetEntity="ScheduleDetail", inversedBy="member_schedule_activity")
     * @ORM\JoinColumn(name="schedule_detail_id", referencedColumnName="id", onDelete="CASCADE")
     */
    public $scheduleDetail;

    /**
     * @ORM\ManyToOne(targetEntity="Package", inversedBy="member_schedule_package")
     * @ORM\JoinColumn(nullable=false, name="package_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $package;

    /**
     * @ORM\ManyToOne(targetEntity="MemberPackage", inversedBy="memberScheduleActivity")
     * @ORM\JoinColumn( name="member_package_id", referencedColumnName="id", )
     */
    private $memberPackage;


    /**
     * @var int
     *
     * @ORM\Column(name="checkin", type="integer")
     */
    public $checkin;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_favourite", type="boolean")
     */
    private $isFavourite = false;

    /**
     * @ORM\Column(name="status", type="string", columnDefinition="enum( 'booked', 'favourite', 'cancelled', 'expired','reserved')")
     */
    public $status = 'booked';


    /**
     * @ORM\OneToMany(targetEntity="MemberScheduleTimelog", mappedBy="book")
     */
    private $memberScheduleTimelog;


    /**
     * Set checkin
     *
     * @param integer $checkin
     *
     * @return MemberScheduleActivity
     */
    public function setCheckin($checkin)
    {
        $this->checkin = $checkin;

        return $this;
    }

    /**
     * Get checkin
     *
     * @return int
     */
    public function getCheckin()
    {
        return $this->checkin;
    }

    /**
     * Set member
     *
     * @param User $member
     *
     * @return MemberScheduleActivity
     */
    public function setMember(User $member = null)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return User
     */
    public function getMember()
    {
        return $this->member;
    }


    public function getImageName()
    {
        return $this->member->getImageName();
    }

    /**
     * Set package
     *
     * @param Package $package
     *
     * @return MemberScheduleActivity
     */
    public function setPackage(Package $package)
    {
        $this->package = $package;

        return $this;
    }

    /**
     * Get package
     *
     * @return Package
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * Set isFavourite
     *
     * @param boolean $isFavourite
     *
     * @return MemberScheduleActivity
     */
    public function setIsFavourite($isFavourite)
    {
        $this->isFavourite = $isFavourite;

        return $this;
    }

    /**
     * Get isFavourite
     *
     * @return boolean
     */
    public function getIsFavourite()
    {
        return $this->isFavourite;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return MemberScheduleActivity
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }


    /**
     * Add memberScheduleTimelog
     *
     * @param MemberScheduleTimelog $memberScheduleTimelog
     *
     * @return MemberScheduleActivity
     */
    public function addMemberScheduleTimelog(MemberScheduleTimelog $memberScheduleTimelog)
    {
        $this->memberScheduleTimelog[] = $memberScheduleTimelog;

        return $this;
    }

    /**
     * Remove memberScheduleTimelog
     *
     * @param MemberScheduleTimelog $memberScheduleTimelog
     */
    public function removeMemberScheduleTimelog(MemberScheduleTimelog $memberScheduleTimelog)
    {
        $this->memberScheduleTimelog->removeElement($memberScheduleTimelog);
    }

    /**
     * Get memberScheduleTimelog
     *
     * @return Collection
     */
    public function getMemberScheduleTimelog()
    {
        return $this->memberScheduleTimelog;
    }


    public function getUsercheckIn($scheduletimetableid)
    {

        return $this->msaHelper->isUserCheckIn($this->getId(), $scheduletimetableid);
    }

    public function getIsUserClassExpired($scheduletimetableid)
    {
       return $this->msaHelper->isUserClassExpired($scheduletimetableid);
    }

    private $msaHelper = null;

    public function msaHelper($msaHelper)
    {
        $this->msaHelper = $msaHelper;
    }

    /**
     * Set scheduleDetail
     *
     * @param ScheduleDetail $scheduleDetail
     *
     * @return MemberScheduleActivity
     */
    public function setScheduleDetail(ScheduleDetail $scheduleDetail = null)
    {
        $this->scheduleDetail = $scheduleDetail;

        return $this;
    }

    /**
     * Get scheduleDetail
     *
     * @return ScheduleDetail
     */
    public function getScheduleDetail()
    {
        return $this->scheduleDetail;
    }

    /**
     * Set memberPackage
     *
     * @param MemberPackage $memberPackage
     *
     * @return MemberScheduleActivity
     */
    public function setMemberPackage(MemberPackage $memberPackage = null)
    {
        $this->memberPackage = $memberPackage;

        return $this;
    }

    /**
     * Get memberPackage
     *
     * @return MemberPackage
     */
    public function getMemberPackage()
    {
        return $this->memberPackage;
    }

    public function getAttendanceStatus()
    {
        try {


//            dump($memberScheduleActivity->getScheduleDetail()->getSchedule()->getIsFree());die;
                if ($this->getScheduleDetail()) {
                    /**
                     * @var MemberScheduleActivity $memberScheduleActivity
                     */
                    if ($this->getCheckin() > 0 && $this->getStatus() != 'cancelled') {
                        return 'Attended';
                    } elseif ($this->getStatus() == 'booked' && $this->getCheckin() <= 0) {
                        return 'No Show';
                    } else if ($this->getStatus() == 'cancelled') {
                        $cancellation = $this->isCancellationInInterval();
                        if ($cancellation) {
                            return 'Early Cancellation';
                        } else {

                            return 'Late Cancellation';
                        }
                    }else{
                        return 'Absent'; ////added later dont know
                    }
                } else {
                    return 'Late Cancellation';
                }

        } catch (\Throwable $t) {
            return 'NA';
        }

    }

    public function getUserPackageType()
    {
        if ($this->getScheduleDetail()) {
            $is_free = $this->getScheduleDetail()->getSchedule()->getIsFree();
        } else {
            $is_free = null;
        }
        if ($this->getMemberPackage()) {
            if ($this->getMemberPackage()->getPackage()) {
                $is_corepass = $this->getMemberPackage()->getPackage()->getIsCorepass();
            } else {
                $is_corepass = null;
            }
        } else {
            $is_corepass = null;
        }

        if ($this->getMemberPackage()) {
            $package_type = strtolower($this->getMemberPackage()->getPackage()->getPackageType()->getName());
        } else {
            $package_type = null;
        }

        if ($is_free == 1) {
            return 'Free Booking';
        } elseif ($is_corepass == 1) {
            return 'Core Pass';
        } elseif ($package_type == 'session') {
            return 'Session Pass Package';
        } elseif ($package_type == 'membership') {
            return 'Unlimited Membership';
        } else {
            return 'NA';
        }
    }


    public function getClassFee()
    {
        if ($this->getMemberPackage()) {
            $fee = $this->getMemberPackage()->getPackage()->getCorporateRate();
            if ($fee) {
                return 'AED ' . $fee;
            } else {
                return 'AED 0';
            }
        } else {
            return 'AED 0';
        }

    }

    public function getFacilityPaymentDue()
    {

        $fee = $this->getPackagePurchaseCost();
        if ($fee != 0) {
            if($this->getAttendanceStatus() == 'Early Cancellation'){
                return 'AED 0';
            }
            if($this->getPackage()->getIsCorepass()){
                return 'AED 50';
            }
            return 'AED ' . $this->getPercentOfNumber($fee);
        } else {
            return 'AED 0';
        }
    }

    public function getPercentOfNumber($number)
    {
        return (85 / 100) * $number;
    }


    public function getClientType()
    {
        global $kernel;
        $em = $kernel->getContainer()->get('doctrine')->getManager();
        /**
         * @var $em EntityManager
         */
        $query = $em->createQueryBuilder('p')
            ->select('DISTINCT ck')
            ->from('App\\Entity\\CorporateKey', 'ck')
            ->innerJoin('ck.facilityPackage','ckp')
            ->innerJoin('App\\Entity\\MemberKey','mk','WITH','ck.id=mk.corporateKey')
            ->where('ckp = :pkg')
            ->andWhere('mk.member = :member')

            ->setParameter('pkg', $this->getPackage())
            ->setParameter('member', $this->getMember())
        ;
//        dump($query->getQuery()->getSQL(),$this->getPackage());die;
        $result =  $query->getQuery()->getResult();
//dump($result);die;
        if(empty($result)){
            return 'Public';
        }else{

            return 'Corporate';
        }


    }

    public function isCancellationInInterval()
    {
        /**
         * @var MemberScheduleActivity $memberScheduleActivity
         */
        $createdDate = $this->getScheduleDetail()->getScheduleDate();
        $updatedDate = $this->getUpdatedDate();
//        $date1 = new DateTime($createdDate);
//        $date2 = new DateTime($updatedDate);
        $diff = $createdDate->diff($updatedDate);
        $hours = $diff->h;
        $hours = $hours + ($diff->days * 24);
        if ($hours > self::CANCELLATION_TIME_INTERVAL) {
            return true;
        } else {
            return false;
        }


    }


    public function getPackageCorporateKey()
    {

        global $kernel;
        $em = $kernel->getContainer()->get('doctrine')->getManager();
        /**
         * @var $em EntityManager
         */
        $query = $em->createQueryBuilder('p')
            ->select('DISTINCT ck')
            ->from('App\\Entity\\CorporateKey', 'ck')
            ->innerJoin('ck.facilityPackage','ckp')
            ->innerJoin('App\\Entity\\MemberKey','mk','WITH','ck.id=mk.corporateKey')
            ->where('ckp = :pkg')
            ->andWhere('mk.member = :member')

            ->setParameter('pkg', $this->getPackage())
            ->setParameter('member', $this->getMember())
        ;
//        dump($query->getQuery()->getSQL(),$this->getPackage());die;
        $result =  $query->getQuery()->getResult();
//dump($result);die;
        if(empty($result)){
            return '';
        }else{
            $result = $result[0];
            return $result->getCompanyKey();
        }
    }

    public function getCorporateName()
    {

        global $kernel;
        $em = $kernel->getContainer()->get('doctrine')->getManager();
        /**
         * @var $em EntityManager
         */
        $query = $em->createQueryBuilder('p')
            ->select('DISTINCT ck')
            ->from('App\\Entity\\CorporateKey', 'ck')
            ->innerJoin('ck.facilityPackage','ckp')
            ->innerJoin('App\\Entity\\MemberKey','mk','WITH','ck.id=mk.corporateKey')
            ->where('ckp = :pkg')
            ->andWhere('mk.member = :member')

            ->setParameter('pkg', $this->getPackage())
            ->setParameter('member', $this->getMember())
        ;
        $result =  $query->getQuery()->getResult();
        if(empty($result)){
            return '';
        }else{
            $result = $result[0];

            return $result->getCorporate()->getCompanyName();
        }
    }

    public function getPackagePurchaseCost()
    {
        global $kernel;
        $em = $kernel->getContainer()->get('doctrine')->getManager();
        /**
         * @var $em EntityManager
         */
        $billingHistory = $em->getRepository('CoredirectionBundle:MemberBillingDetail')->findOneBy(array(
            'memberPackage' => $this->getMemberPackage()
        ));

        if($billingHistory){
            return $billingHistory->getAmount();
//            return 'this';
        }else{
            return $this->getPackage()->getPrice();
        }

    }



    public function getSessionsConsumed()
    {
        $getMemberPackageSessionCount = $this->getMemberPackage()->getCheckin();
        if($this->getAttendanceStatus() == 'No Show'){
            $getMemberPackageSessionCount = 0;
        }
        elseif($this->getAttendanceStatus() == 'Early Cancellation'){
            $getMemberPackageSessionCount = 0;
        }
        return (string)$getMemberPackageSessionCount . '  \  ' .  $this->getPackage()->getVisits();
    }


    public function getPackageActivityType()
    {
        try {


            /**
             * @var  $schdualDetail ScheduleDetail
             */
            $schdualDetail = $this->getScheduleDetail();

            if(!$schdualDetail){

                return "";
            }

            /**
             * @var  $activityScduale ActivitySchedule
             */
            $activityScduale = $schdualDetail->getSchedule();
            if(!$activityScduale){

                return "";
            }
            $activity = $activityScduale->getActivity();
//
            if(!$activity){

                return "";
            }

            /**
             * @var $activityType ActivityType
             */
            $activityType = $activity->getActivityType();

            if(!$activityType){

                return "";
            }
            return $activityType->getName();

        } catch (\Throwable $t) {
            return '';
        } catch (NoSuchPropertyException $d) {
            return '';
        }


    }



    public function getClassDetailsName()
    {
        try {


                return $this->getScheduleDetail()->getSchedule()->getActivity()->getName();


        } catch (\Throwable $t) {
            return '';
        } catch (NoSuchPropertyException $d) {
            return '';
        }
    }



    public function getClassDate()
    {
        try {

                return $this->getScheduleDetail()->getScheduleDateForExport();

        } catch (\Throwable $t) {
            return '';
        } catch (NoSuchPropertyException $d) {
            return '';
        }
    }

    /**
     * @return string
     */
    public function getClassTime()
    {
        try {


                return $this->getScheduleDetail()->getScheduleTimeForExport();

        } catch (\Throwable $t) {
            return '';
        } catch (NoSuchPropertyException $d) {
            return '';
        }
    }















}
