<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Package
 *
 * @ORM\Table(name="package")
 * @ORM\Entity(repositoryClass="App\Repository\PackageRepository")
 */
class Package extends BaseEntity
{
    public function __construct()
    {
        $this->activitySchedule = new ArrayCollection();
        $this->facilityPackage = new ArrayCollection();
        $this->memberPackage = new ArrayCollection();
        $this->member_schedule_package = new ArrayCollection();
        $this->memberBillingDetail = new ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this->name;
    }


    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=50)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     *
     * @ORM\ManyToOne(targetEntity="PackageType", inversedBy="package")
     * @ORM\JoinColumn(name="package_type_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $packageType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expires_on", type="datetime", nullable=true)
     */
    private $expireson;

    /**
     * @var int
     *
     * @ORM\Column(name="visits", type="integer")
     */
    private $visits;


    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="package")
     * @ORM\JoinColumn(name="facility_id", referencedColumnName="id")
     */
    private $facility;

    /**
     * @var string
     *
     * @ORM\Column(name="public_rate", type="decimal", precision=10, scale=2)
     */
        private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="corporate_rate", type="decimal", precision=10, scale=2)
     */
    private $corporateRate;

    /**
     * @var string
     *
     * @ORM\Column(name="discount", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $discount;


    /**
     * @ORM\OneToMany(targetEntity="FacilityPackage", mappedBy="package")
     */
    private $facilityPackage;

    /**
     * @ORM\OneToMany(targetEntity="MemberPackage", mappedBy="package")
     */
    private $memberPackage;

    /**
     * @ORM\OneToMany(targetEntity="MemberScheduleActivity", mappedBy="package")
     */
    private $member_schedule_package;

    /**
     * @ORM\Column(name="validity_days", type="smallint", options={"default":0})
     */
    private $validityDays = 0;

    /**
     * @ORM\Column(name="repeat_monthly", type="boolean", options={"default":0})
     */
    private $repeatMonthly = false;

    /**
     * @ORM\Column(name="individual_rate_commission" , type="decimal", precision=10, scale=2 )
     */
    private $individualRateCommission;
    /**
     * @ORM\Column(name="corporate_rate_commission",  type="decimal", precision=10, scale=2 )
     */
    private $corporateRateCommission;

    /**
     * @ORM\OneToMany(targetEntity="MemberBillingDetail", mappedBy="package")
     */
    private $memberBillingDetail;

    /**
     * @ORM\Column(name="is_corepass", type="boolean", nullable=true)
     */
    private $isCorepass = false;

    /**
     * @return mixed
     */
    public function getMemberPackage()
    {
        return $this->memberPackage;
    }


    /**
     * @return mixed
     */
    public function getFacilityPackage()
    {
        return $this->facilityPackage;
    }


    /**
     * Set code
     *
     * @param string $code
     *
     * @return Package
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Package
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Package
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * Set visits
     *
     * @param integer $visits
     *
     * @return Package
     */
    public function setVisits($visits)
    {
        $this->visits = $visits;

        return $this;
    }

    /**
     * Get visits
     *
     * @return int
     */
    public function getVisits()
    {
        return $this->visits;
    }


    /**
     * Set facility
     *
     * @param integer $facility
     *
     * @return Package
     */
    public function setFacility($facility)
    {
        $this->facility = $facility;

        return $this;
    }

    /**
     * Get facility
     *
     * @return int
     */
    public function getFacility()
    {
        return $this->facility;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Package
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set discount
     *
     * @param string $discount
     *
     * @return Package
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return string
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set packageType
     *
     * @param PackageType $packageType
     *
     * @return Package
     */
    public function setPackageType(PackageType $packageType = null)
    {
        $this->packageType = $packageType;

        return $this;
    }

    /**
     * Get packageType
     *
     * @return PackageType
     */
    public function getPackageType()
    {
        return $this->packageType;
    }






    /**
     * Set expireson
     *
     * @param \DateTime $expireson
     *
     * @return Package
     */
    public function setExpireson($expireson)
    {
        $this->expireson = $expireson;

        return $this;
    }

    /**
     * Get expireson
     *
     * @return \DateTime
     */
    public function getExpireson()
    {
        return $this->expireson;
    }

    /**
     * Set corporateRate
     *
     * @param string $corporateRate
     *
     * @return Package
     */
    public function setCorporateRate($corporateRate)
    {
        $this->corporateRate = $corporateRate;

        return $this;
    }

    /**
     * Get corporateRate
     *
     * @return string
     */
    public function getCorporateRate()
    {
        return $this->corporateRate;
    }

    /**
     * Set validityDays
     *
     * @param integer $validityDays
     *
     * @return Package
     */
    public function setValidityDays($validityDays)
    {
        $this->validityDays = $validityDays;

        return $this;
    }

    /**
     * Get validityDays
     *
     * @return integer
     */
    public function getValidityDays()
    {
        return $this->validityDays;
    }

    /**
     * Set repeatMonthly
     *
     * @param integer $repeatMonthly
     *
     * @return Package
     */
    public function setRepeatMonthly($repeatMonthly)
    {
        $this->repeatMonthly = $repeatMonthly;

        return $this;
    }

    /**
     * Get repeatMonthly
     *
     * @return integer
     */
    public function getRepeatMonthly()
    {
        return $this->repeatMonthly;
    }

    /**
     * Add facilityPackage
     *
     * @param FacilityPackage $facilityPackage
     *
     * @return Package
     */
    public function addFacilityPackage(FacilityPackage $facilityPackage)
    {
        $this->facilityPackage[] = $facilityPackage;

        return $this;
    }

    /**
     * Remove facilityPackage
     *
     * @param FacilityPackage $facilityPackage
     */
    public function removeFacilityPackage(FacilityPackage $facilityPackage)
    {
        $this->facilityPackage->removeElement($facilityPackage);
    }

    /**
     * Add memberPackage
     *
     * @param MemberPackage $memberPackage
     *
     * @return Package
     */
    public function addMemberPackage(MemberPackage $memberPackage)
    {
        $this->memberPackage[] = $memberPackage;

        return $this;
    }

    /**
     * Remove memberPackage
     *
     * @param MemberPackage $memberPackage
     */
    public function removeMemberPackage(MemberPackage $memberPackage)
    {
        $this->memberPackage->removeElement($memberPackage);
    }

    public function getImageName()
    {
        if ($this->facility) {
            return $this->facility->getCompanyLogo();
        } else {
            return false;
        }
    }

    /**
     * Set individualRateCommission
     *
     * @param string $individualRateCommission
     *
     * @return Package
     */
    public function setIndividualRateCommission($individualRateCommission)
    {
        $this->individualRateCommission = $individualRateCommission;

        return $this;
    }

    /**
     * Get individualRateCommission
     *
     * @return string
     */
    public function getIndividualRateCommission()
    {
        return $this->individualRateCommission;
    }

    /**
     * Set corporateRateCommission
     *
     * @param string $corporateRateCommission
     *
     * @return Package
     */
    public function setCorporateRateCommission($corporateRateCommission)
    {
        $this->corporateRateCommission = $corporateRateCommission;

        return $this;
    }

    /**
     * Get corporateRateCommission
     *
     * @return string
     */
    public function getCorporateRateCommission()
    {
        return $this->corporateRateCommission;
    }

    /**
     * Add memberSchedulePackage
     *
     * @param MemberScheduleActivity $memberSchedulePackage
     *
     * @return Package
     */
    public function addMemberSchedulePackage(MemberScheduleActivity $memberSchedulePackage)
    {
        $this->member_schedule_package[] = $memberSchedulePackage;

        return $this;
    }

    /**
     * Remove memberSchedulePackage
     *
     * @param MemberScheduleActivity $memberSchedulePackage
     */
    public function removeMemberSchedulePackage(MemberScheduleActivity $memberSchedulePackage)
    {
        $this->member_schedule_package->removeElement($memberSchedulePackage);
    }

    /**
     * Get memberSchedulePackage
     *
     * @return Collection
     */
    public function getMemberSchedulePackage()
    {
        return $this->member_schedule_package;
    }

    /**
     * Add memberBillingDetail
     *
     * @param MemberBillingDetail $memberBillingDetail
     *
     * @return Package
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
     * Set isCorepass
     *
     * @param boolean $isCorepass
     *
     * @return Package
     */
    public function setIsCorepass($isCorepass)
    {
        $this->isCorepass = $isCorepass;

        return $this;
    }

    /**
     * Get isCorepass
     *
     * @return boolean
     */
    public function getIsCorepass()
    {
        return $this->isCorepass;
    }


    public function getPackageExpiryDate()
    {
        return $this->getExpireson()->format('y-m-d');
    }

    public function getPackagePrice()
    {
        if($this->getPrice()){
            return 'AED ' .$this->getPrice();
        }else{
            return 'AED 0';
        }

    }




}
