<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * CorporateKey
 *
 * @ORM\Table(name="corporate_key")
 * @ORM\Entity(repositoryClass="App\Repository\CorporateKeyRepository")
 */
class CorporateKey extends BaseEntity
{

    public function __construct()
    {
        $this->key = new ArrayCollection();
        $this->facilityPackage = new ArrayCollection();
    }


    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="corporateKey")
     * @ORM\JoinColumn(name="corporate_id", referencedColumnName="id")
     */
    private $corporate;

    /**
     * @var string
     *
     * @ORM\Column(name="company_key", type="string", length=25)
     */
    private $companyKey;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="validate_date", type="datetime")
     */
    private $validateDate;

    /**
     * @ORM\OneToMany(targetEntity="MemberKey", mappedBy="corporateKey")
     */
    protected $key;

    /**
     * @ORM\ManyToMany(targetEntity="Package")
     * @ORM\JoinTable(name="corporate_key_package",
     *      joinColumns={@ORM\JoinColumn(onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(onDelete="CASCADE")}
     * )
     */
    private $facilityPackage;

    /**
     * @ORM\ManyToMany(targetEntity="ProfileKey")
     * @ORM\JoinTable(name="key_profile_group",
     *      joinColumns={@ORM\JoinColumn(onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(onDelete="CASCADE")}
     * )
     */
    private $keyProfile;

    /**
     *
     * @ORM\Column(name="type", type="string", columnDefinition="enum('Discount', 'Package', 'CorePass', 'Profile', 'Default','Referral')")
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime")
     */
    private $startDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=true, options={"default":1})
     */
    private $isActive = true;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MemberInvitation",mappedBy="corporate_key")
     */
    private $memberInvitation;
    /**
     * Set corporate
     *
     * @param integer $corporate
     * @return CorporateKey
     */
    public function setCorporate($corporate)
    {
        $this->corporate = $corporate;

        return $this;
    }

    /**
     * Get corporate
     *
     * @return integer
     */
    public function getCorporate()
    {
        return $this->corporate;
    }

    /**
     * Set companyKey
     *
     * @param string $companyKey
     * @return CorporateKey
     */
    public function setCompanyKey($companyKey)
    {
        $this->companyKey = $companyKey;

        return $this;
    }

    /**
     * Get companyKey
     *
     * @return string
     */
    public function getCompanyKey()
    {
        return $this->companyKey;
    }

    /**
     * Set validateDate
     *
     * @param \DateTime $validateDate
     * @return CorporateKey
     */
    public function setValidateDate($validateDate)
    {
        $this->validateDate = $validateDate;

        return $this;
    }

    /**
     * Get validateDate
     *
     * @return \DateTime
     */
    public function getValidateDate()
    {
        return $this->validateDate;
    }

    /**
     * Add key
     *
     * @param MemberKey $key
     * @return CorporateKey
     */
    public function addKey(MemberKey $key)
    {
        $this->key[] = $key;

        return $this;
    }

    /**
     * Remove key
     *
     * @param MemberKey $key
     */
    public function removeKey(MemberKey $key)
    {
        $this->key->removeElement($key);
    }

    /**
     * Get key
     *
     * @return Collection
     */
    public function getKey()
    {
        return $this->key;
    }
    
        
    public function __toString(){
        return 'Corporate Key';
    }

    public function getImageName(){
        return $this->corporate->getCompanyLogo();
    }

    /**
     * Add facilityPackage
     *
     * @param Package $facilityPackage
     *
     * @return CorporateKey
     */
    public function addFacilityPackage(Package $facilityPackage)
    {
        $this->facilityPackage[] = $facilityPackage;

        return $this;
    }

    /**
     * Remove facilityPackage
     *
     * @param Package $facilityPackage
     */
    public function removeFacilityPackage(Package $facilityPackage)
    {
        $this->facilityPackage->removeElement($facilityPackage);
    }

    /**
     * Get facilityPackage
     *
     * @return Collection
     */
    public function getFacilityPackage()
    {
        return $this->facilityPackage;
    }

    /**
     * Set isDiscountOnly
     *
     * @param boolean $isDiscountOnly
     *
     * @return CorporateKey
     */
    public function setIsDiscountOnly($isDiscountOnly)
    {
        $this->isDiscountOnly = $isDiscountOnly;

        return $this;
    }

    /**
     * Get isDiscountOnly
     *
     * @return boolean
     */
    public function getIsDiscountOnly()
    {
        return $this->isDiscountOnly;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return CorporateKey
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
     * Add keyId
     *
     * @param User $keyId
     *
     * @return CorporateKey
     */
    public function addKeyId(User $keyId)
    {
        $this->keyId[] = $keyId;

        return $this;
    }

    /**
     * Remove keyId
     *
     * @param User $keyId
     */
    public function removeKeyId(User $keyId)
    {
        $this->keyId->removeElement($keyId);
    }

    /**
     * Get keyId
     *
     * @return Collection
     */
    public function getKeyId()
    {
        return $this->keyId;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return CorporateKey
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add keyProfile
     *
     * @param ProfileKey $keyProfile
     *
     * @return CorporateKey
     */
    public function addKeyProfile(ProfileKey $keyProfile)
    {
        $this->keyProfile[] = $keyProfile;

        return $this;
    }

    /**
     * Remove keyProfile
     *
     * @param ProfileKey $keyProfile
     */
    public function removeKeyProfile(ProfileKey $keyProfile)
    {
        $this->keyProfile->removeElement($keyProfile);
    }

    /**
     * Get keyProfile
     *
     * @return Collection
     */
    public function getKeyProfile()
    {
        return $this->keyProfile;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return CorporateKey
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @return mixed
     */
    public function getMemberInvitation()
    {
        return $this->memberInvitation;
    }

    /**
     * @param mixed $memberInvitation
     */
    public function setMemberInvitation($memberInvitation)
    {
        $this->memberInvitation = $memberInvitation;
    }


}
