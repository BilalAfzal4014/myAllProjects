<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * UserBankInfo
 *
 * @ORM\Table(name="user_bank_info")
 * @ORM\Entity(repositoryClass="App\Repository\UserBankInfoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class UserBankInfo
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
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="UserBankInfo")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    /**
     * @var string
     *
     * @ORM\Column(name="account_title", type="string", length=100, nullable=true)
     */
    private $accountTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="bank_name", type="string", length=100 , nullable=true)
     */
    private $bankName;

    /**
     * @var string
     *
     * @ORM\Column(name="iban", type="string", length=255 , nullable=true)
     */
    private $IBAN;

    /**
     * @var string
     *
     * @ORM\Column(name="branch", type="string", length=100 , nullable=true)
     */
    private $branch;

    /**
     * @var string
     *
     * @ORM\Column(name="account_no", type="text" , nullable=true)
     */
    private $accountNo;

    /**
     * @var int
     *
     * @ORM\Column(name="branch_code", type="smallint" , nullable=true)
     */
    private $branchCode;

    /**
     * @var string
     *
     * @ORM\Column(name="swift_code", type="string", length=255 , nullable=true)
     */
    private $swiftCode;

    /**
     * @var string
     *
     * @ORM\Column(name="bank_address", type="string", length=255 , nullable=true)
     */
    private $bankAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="secret_key", type="string", length=100, nullable=true  )
     */
    private $secretKey;

    /**
     * @var string
     *
     * @ORM\Column(name="publish_key", type="string", length=100, nullable=true)
     */
    private $publishKey;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime")
     */
    private $createdDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_date", type="datetime")
     */
    private $updatedDate;

    /**
     * @ORM\Column(name="currency_code", type="string", columnDefinition="enum('AED', 'GBP', 'USD')")
     */
    private $currencyCode;

    /**
     * @ORM\Column(name="country_code", type="string", length=2  , nullable=true)
     */
    private $countryCode;
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
     * Set accountTitle
     *
     * @param string $accountTitle
     *
     * @return UserBankInfo
     */
    public function setAccountTitle($accountTitle)
    {
        $this->accountTitle = $accountTitle;

        return $this;
    }

    /**
     * Get accountTitle
     *
     * @return string
     */
    public function getAccountTitle()
    {
        return $this->accountTitle;
    }

    /**
     * Set bankName
     *
     * @param string $bankName
     *
     * @return UserBankInfo
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;

        return $this;
    }

    /**
     * Get bankName
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Set IBAN
     *
     * @param string $IBAN
     *
     * @return UserBankInfo
     */
    public function setIBAN($IBAN)
    {
        $this->IBAN = $IBAN;

        return $this;
    }

    /**
     * Get iBAN
     *
     * @return string
     */
    public function getIBAN()
    {
        return $this->IBAN;
    }

    /**
     * Set branch
     *
     * @param string $branch
     *
     * @return UserBankInfo
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;

        return $this;
    }

    /**
     * Get branch
     *
     * @return string
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * Set accountNo
     *
     * @param string $accountNo
     *
     * @return UserBankInfo
     */
    public function setAccountNo($accountNo)
    {
        $this->accountNo = $accountNo;

        return $this;
    }

    /**
     * Get accountNo
     *
     * @return string
     */
    public function getAccountNo()
    {
        return $this->accountNo;
    }

    /**
     * Set branchCode
     *
     * @param integer $branchCode
     *
     * @return UserBankInfo
     */
    public function setBranchCode($branchCode)
    {
        $this->branchCode = $branchCode;

        return $this;
    }

    /**
     * Get branchCode
     *
     * @return int
     */
    public function getBranchCode()
    {
        return $this->branchCode;
    }

    /**
     * Set swiftCode
     *
     * @param string $swiftCode
     *
     * @return UserBankInfo
     */
    public function setSwiftCode($swiftCode)
    {
        $this->swiftCode = $swiftCode;

        return $this;
    }

    /**
     * Get swiftCode
     *
     * @return string
     */
    public function getSwiftCode()
    {
        return $this->swiftCode;
    }

    /**
     * Set bankAddress
     *
     * @param string $bankAddress
     *
     * @return UserBankInfo
     */
    public function setBankAddress($bankAddress)
    {
        $this->bankAddress = $bankAddress;

        return $this;
    }

    /**
     * Get bankAddress
     *
     * @return string
     */
    public function getBankAddress()
    {
        return $this->bankAddress;
    }

    /**
     * Set secretKey
     *
     * @param string $secretKey
     *
     * @return UserBankInfo
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;

        return $this;
    }

    /**
     * Get secretKey
     *
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * Set publishKey
     *
     * @param string $publishKey
     *
     * @return UserBankInfo
     */
    public function setPublishKey($publishKey)
    {
        $this->publishKey = $publishKey;

        return $this;
    }

    /**
     * Get publishKey
     *
     * @return string
     */
    public function getPublishKey()
    {
        return $this->publishKey;
    }

    /**
     * @ORM\PrePersist
     */
    public function onCreate()
    {
        $this->createdDate = new \DateTime();
        $this->updatedDate = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function onUpdate()
    {
        $this->updatedDate = new \DateTime();
    }


    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return UserBankInfo
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set updatedDate
     *
     * @param \DateTime $updatedDate
     *
     * @return UserBankInfo
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    /**
     * Get updatedDate
     *
     * @return \DateTime
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return UserBankInfo
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set currencyCode
     *
     * @param string $currencyCode
     *
     * @return UserBankInfo
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;

        return $this;
    }

    /**
     * Get currencyCode
     *
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * Set countryCode
     *
     * @param string $countryCode
     *
     * @return UserBankInfo
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get countryCode
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }
}
