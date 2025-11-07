<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MemberBillingDetail
 *
 * @ORM\Table(name="member_billing_detail")
 * @ORM\Entity(repositoryClass="App\Repository\MemberBillingDetailRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MemberBillingDetail
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
     * @ORM\ManyToOne(targetEntity="MemberBillingHistory", inversedBy="memberBillingDetail")
     * @ORM\JoinColumn(name="member_billing_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $memberbilling;

    /**
     * @ORM\ManyToOne(targetEntity="MemberPackage", inversedBy="memberBillingDetail")
     * @ORM\JoinColumn(name="member_package_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $memberPackage;

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
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal", precision=10, scale=2)
     */
    private $amount;

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
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return MemberBillingDetail
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
     * @return MemberBillingDetail
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
     * Set memberbilling
     *
     * @param MemberBillingHistory $memberbilling
     *
     * @return MemberBillingDetail
     */
    public function setMemberbilling(MemberBillingHistory $memberbilling = null)
    {
        $this->memberbilling = $memberbilling;

        return $this;
    }

    /**
     * Get memberbilling
     *
     * @return MemberBillingHistory
     */
    public function getMemberbilling()
    {
        return $this->memberbilling;
    }





    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return MemberBillingDetail
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set memberPackage
     *
     * @param MemberPackage $memberPackage
     *
     * @return MemberBillingDetail
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
}
