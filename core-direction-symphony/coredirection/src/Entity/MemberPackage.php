<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Base;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;

/**
 * MemberPackage
 *
 * @ORM\Table(name="member_package")
 * @ORM\Entity(repositoryClass="App\Repository\MemberPackageRepository")
 */
class MemberPackage extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="memberPackage")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
    private $member;


    /**
     * @ORM\ManyToOne(targetEntity="Package", inversedBy="memberPackage")
     * @ORM\JoinColumn(name="package_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $package;

    /**
     * @ORM\Column(name="status", type="string", columnDefinition="enum( 'active', 'cancel', 'expired')")
     */
    private $status = 'active';

    /**
     * @var string
     *
     * @ORM\Column(name="checkin", type="integer", options={"default"=0})
     */
    private $checkin;

    /**
     * @var string
     *
     * @ORM\Column(name="card_id", type="string", length=100, nullable=true)
     *
     */
    private $card;

    /**
     * @ORM\OneToMany(targetEntity="MemberBillingDetail", mappedBy="memberPackage")
     */
    private $memberBillingDetail;

    /**
     * @ORM\OneToMany(targetEntity="MemberScheduleActivity", mappedBy="memberPackage")
     */
    private $memberScheduleActivity;
    /**
     * @ORM\Column(name="is_promotion", type="boolean")
     *
     */
    private $isPromotion = false;

    /**
     * Set isExpired
     *
     * @param boolean $isExpired
     *
     * @return MemberPackage
     */
    public function setIsExpired($isExpired)
    {
        $this->isExpired = $isExpired;

        return $this;
    }

    /**
     * Get isExpired
     *
     * @return bool
     */
    public function getIsExpired()
    {
        return $this->isExpired;
    }

    /**
     * Set checkin
     *
     * @param string $checkin
     *
     * @return MemberPackage
     */
    public function setCheckin($checkin)
    {
        $this->checkin = $checkin;

        return $this;
    }

    /**
     * Get checkin
     *
     * @return string
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
     * @return MemberPackage
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

    /**
     * Set package
     *
     * @param Package $package
     *
     * @return MemberPackage
     */
    public function setPackage(Package $package = null)
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

    public function getimageName()
    {
        return $this->member->getCompanyLogo();
    }

    /**
     * Set card
     *
     * @param string $card
     *
     * @return MemberPackage
     */
    public function setCard($card)
    {
        $this->card = $card;

        return $this;
    }

    /**
     * Get card
     *
     * @return string
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * Set isPromotion
     *
     * @param boolean $isPromotion
     *
     * @return MemberPackage
     */
    public function setIsPromotion($isPromotion)
    {
        $this->isPromotion = $isPromotion;

        return $this;
    }

    /**
     * Get isPromotion
     *
     * @return boolean
     */
    public function getIsPromotion()
    {
        return $this->isPromotion;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return MemberPackage
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
     * Constructor
     */
    public function __construct()
    {
        $this->memberBillingDetail = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add memberBillingDetail
     *
     * @param MemberBillingDetail $memberBillingDetail
     *
     * @return MemberPackage
     */
    public function addMemberBillingDetail(MemberBillingDetail $memberBillingDetail)
    {
        $this->memberBillingDetail[] = $memberBillingDetail;

        return $this;
    }

    /**
     * Remove memberBillingDetail
     *
     * @param MemberBillingDetail $memberBillingDetail
     */
    public function removeMemberBillingDetail(MemberBillingDetail $memberBillingDetail)
    {
        $this->memberBillingDetail->removeElement($memberBillingDetail);
    }

    /**
     * Get memberBillingDetail
     *
     * @return Collection
     */
    public function getMemberBillingDetail()
    {
        return $this->memberBillingDetail;
    }

    /**
     * Add memberScheduleActivity
     *
     * @param MemberScheduleActivity $memberScheduleActivity
     *
     * @return MemberPackage
     */
    public function addMemberScheduleActivity(MemberScheduleActivity $memberScheduleActivity)
    {
        $this->memberScheduleActivity[] = $memberScheduleActivity;

        return $this;
    }

    /**
     * Remove memberScheduleActivity
     *
     * @param MemberScheduleActivity $memberScheduleActivity
     */
    public function removeMemberScheduleActivity(MemberScheduleActivity $memberScheduleActivity)
    {
        $this->memberScheduleActivity->removeElement($memberScheduleActivity);
    }

    /**
     * Get memberScheduleActivity
     *
     * @return Collection
     */
    public function getMemberScheduleActivity()
    {
        return $this->memberScheduleActivity;
    }

    public function getUserClientType()
    {
        $groups = $this->getMember()->getGroups();
        $groupData = array();
        foreach ($groups as $group) {
            $groupData[] = strtolower($group->getCode());
        }
        return $groupData[0];
    }

    /**
     * @return string
     */
    public function getClientType()
    {
//        global $kernel;
//        $groups = [];
//        $em = $kernel->getContainer()->get('doctrine')->getManager();
//        $memberObject = $this->getMember();
//        $memberKeys = $em->getRepository("App\Entity\MemberKey")->findBy(array(
//            'member' => $memberObject->getId()
//        ));
//        foreach ($memberKeys as $key) {
//            $coperateKey = $em->getRepository("App\Entity\CorporateKey")->find($key->getCorporateKey()->getId());
//            $user = $coperateKey->getCorporate();
//            $g = $user->getGroupNames();
//            $groups[] = $g[0];
//        }
////        if($this->getMember()->getId() == 325){
////            dump($groups);die;
////        }
//
//        if (in_array('Corporate', $groups)) {
//            return 'Corporate';
//        } else {
//            return 'Public';
//        }


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

    /***
     * @return string
     */
    public function getPackageCommissionByClientType()
    {
        $clientType = $this->getClientType();
        if ($clientType == 'Corporate') {
            return $this->getPercentOfNumber($this->getPackage()->getCorporateRateCommission());
        } else {
            return $this->getPercentOfNumber($this->getPackage()->getIndividualRateCommission());
        }
    }

    /**
     * @return string
     */
    public function getPaymentDueByMember()
    {
        global $kernel;
        $groups = [];
        $em = $kernel->getContainer()->get('doctrine')->getManager();
        $memberObject = $this->getMember();
        $packageObject = $this->getPackage();
        $paymentObject = $em->getRepository("App\Entity\MemberBillingDetail")->findOneBy([
            'memberPackage' => $this
        ]);
        if ($paymentObject) {
            return 'AED ' . $paymentObject->getAmount();

        } else {
            return 'AED 0';
        }


    }

    /**
     * @return string
     */
    public function getPackageCreatedDateStr()
    {
        return $this->getCreatedDate()->format('Y-M-d');
    }

    /**
     * @return string
     */

    public function getPackageCost()
    {
        $price = $this->getPackage()->getPrice();
        if ($price) {
            return 'AED ' . $price;
        } else {
            return 'AED 0';
        }
    }

    /**
     * @return mixed|string
     */
    public function getPackageActivityType()
    {
        try {
            global $kernel;
            $resullt = [];
            $em = $kernel->getContainer()->get('doctrine')->getManager();
            $memberScheduleActivity = $em->getRepository("App\Entity\MemberScheduleActivity")->findOneBy(array(
                'memberPackage' => $this,
                'package' => $this->getPackage(),
            ));
            if ($memberScheduleActivity)
                return $memberScheduleActivity->getScheduleDetail()->getSchedule()->getActivity()->getActivityType()->getName();
            else
                return '';

        } catch (\Throwable $t) {
            return '';
        } catch (NoSuchPropertyException $d) {
            return '';
        }


    }

    /**
     * @return string
     */
    public function getSessionsConsumed()
    {

        global $kernel;
        $em = $kernel->getContainer()->get('doctrine')->getManager();
        $memberScheduleActivity = $em->getRepository("App\Entity\MemberScheduleActivity")->findBy(array(
            'memberPackage' => $this,
            'package' => $this->getPackage(),
            'checkin' => 1
        ));
        $totalSessions = $this->getPackage()->getVisits();
        if($this->getId() == 297){
//            dump(count($memberScheduleActivity) . '/' . $totalSessions);die;
        }
        return (string)count($memberScheduleActivity) . '  \  ' . $totalSessions;
    }

    /**
     * @param $arr
     * @return bool
     */
    function isHomogenous($arr)
    {
        $firstValue = current($arr);
        foreach ($arr as $val) {
            if ($firstValue !== $val) {
                return false;
            }
        }
        return true;
    }


    /**
     * @return string
     */
    public function getClassDetailsName()
    {
        try {
            global $kernel;
            $resullt = [];
            $em = $kernel->getContainer()->get('doctrine')->getManager();
            $memberScheduleActivity = $em->getRepository("App\Entity\MemberScheduleActivity")->findOneBy(array(
                'memberPackage' => $this,
                'package' => $this->getPackage(),
            ));
            if ($memberScheduleActivity)
                return $memberScheduleActivity->getScheduleDetail()->getSchedule()->getActivity()->getName();
            else
                return '';

        } catch (\Throwable $t) {
            return '';
        } catch (NoSuchPropertyException $d) {
            return '';
        }
    }

    /**
     * @return string
     */
    public function getClassDate()
    {
        try {
            global $kernel;
            $resullt = [];
            $em = $kernel->getContainer()->get('doctrine')->getManager();
            $memberScheduleActivity = $em->getRepository("App\Entity\MemberScheduleActivity")->findOneBy(array(
                'memberPackage' => $this,
                'package' => $this->getPackage(),
            ));
            if ($memberScheduleActivity)
                return $memberScheduleActivity->getScheduleDetail()->getScheduleDateForExport();
            else
                return '';

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
            global $kernel;
            $resullt = [];
            $em = $kernel->getContainer()->get('doctrine')->getManager();
            $memberScheduleActivity = $em->getRepository("App\Entity\MemberScheduleActivity")->findOneBy(array(
                'memberPackage' => $this,
                'package' => $this->getPackage(),
            ));
            if ($memberScheduleActivity)
                return $memberScheduleActivity->getScheduleDetail()->getScheduleTimeForExport();
            else
                return '';
//            return  $memberScheduleActivity->getScheduleDetail()->getScheduleTimeForExport();
        } catch (\Throwable $t) {
            return '';
        } catch (NoSuchPropertyException $d) {
            return '';
        }
    }

    /**
     * @return string
     */
    public function getAttendanceStatus()
    {
        try {
            global $kernel;
            $resullt = [];
            $em = $kernel->getContainer()->get('doctrine')->getManager();
            $memberScheduleActivity = $em->getRepository("App\Entity\MemberScheduleActivity")->findOneBy(array(
                'memberPackage' => $this,
                'package' => $this->getPackage(),
            ));
            if ($memberScheduleActivity) {
//            dump($memberScheduleActivity->getScheduleDetail()->getSchedule()->getIsFree());die;
                if ($memberScheduleActivity->getScheduleDetail()) {
                    /**
                     * @var MemberScheduleActivity $memberScheduleActivity
                     */
                    if ($memberScheduleActivity->getScheduleDetail()->getSchedule()->getIsFree() == 1) {
                        return 'Free';
                    } elseif ($memberScheduleActivity->checkin > 0 && $memberScheduleActivity->status != 'cancelled') {
                        return 'Attended';
                    } elseif ($memberScheduleActivity->status == 'booked' && $memberScheduleActivity->checkin <= 0) {
                        return 'No Show';
                    } else if ($memberScheduleActivity->status == 'cancelled') {
                        $cancellation = $this->isCancellationInInterval($memberScheduleActivity);
                        if ($cancellation) {
                            return 'Early Cancellation';
                        } else {

                            return 'Late Cancellation';
                        }
                    }
                } else {
                    return 'Cancellation';
                }
            } else {
                return '';
            }
        } catch (\Throwable $t) {
            return 'NA';
        }

    }


    /**
     * @return string
     */
    public function getUserPackageType()
    {
        try {
            global $kernel;
            $resullt = [];
            $em = $kernel->getContainer()->get('doctrine')->getManager();
            $memberScheduleActivity = $em->getRepository("App\Entity\MemberScheduleActivity")->findOneBy(array(
                'memberPackage' => $this,
                'package' => $this->getPackage(),
            ));
            if ($memberScheduleActivity)
                return $memberScheduleActivity->getScheduleDetail()->getUserPackageType();
            else
                return '';
//            return
        } catch (\Throwable $t) {
            return '';
        } catch (NoSuchPropertyException $d) {
            return '';
        }
    }

    /**
     * @return string
     */
    public function getClassFee()
    {

        $fee = $this->getPackage()->getCorporateRate();
        if ($fee) {
            return 'AED ' . $fee;
        } else {
            return 'AED 0';
        }

    }

    /**
     * @return string
     */
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

    /**
     * @param $number
     * @return float|int
     */
    public function getPercentOfNumber($number)
    {
        return (85 / 100) * $number;
    }
    /**
     * @return bool
     */
    public function isCancellationInInterval($memberScheduleActivity)
    {
        /**
         * @var MemberScheduleActivity $memberScheduleActivity
         */
        $createdDate = $memberScheduleActivity->getCreatedDate();
        $updatedDate = $memberScheduleActivity->getUpdatedDate();
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
            'memberPackage' => $this
        ));

        if($billingHistory){
            return $billingHistory->getAmount();
//            return 'this';
        }else{
            return $this->getPackage()->getPrice();
        }

    }




}
