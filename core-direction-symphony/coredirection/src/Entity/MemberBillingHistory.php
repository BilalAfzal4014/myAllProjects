<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * BillingHistory
 *
 * @ORM\Table(name="member_billing")
 * @ORM\Entity(repositoryClass="App\Repository\BillingHistoryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MemberBillingHistory
{

    public function __construct()
    {
        $this->memberBillingDetail =  new ArrayCollection();
    }
    private $mbhHelper = null;

    public function mbhHelper($mbhHelper)
    {
        $this->mbhHelper = $mbhHelper;
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="memberBillingHistory")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;
    /**
     * @var string
     *
     * @ORM\Column(name="charge_id", type="string", length=100)
     */
    private $charge;

    /**
     * @var string
     *
     * @ORM\Column(name="track_id", type="string", length=100)
     */
    private $track;

    /**
     * @var string
     *
     * @ORM\Column(name="card_id", type="string", length=100)
     */
    private $card;

    /**
     * @var string
     *
     * @ORM\Column(name="last_four", type="string", length=4)
     */
    private $lastFour;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal", precision=10, scale=2)
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="vat", type="decimal", precision=10, scale=2)
     */
    private $vat;
    
    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=3)
     */
    private $currency;

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
     * @ORM\Column(name="transaction_response", type="json")
     */
    private $transactionResponse;


    /**
     * @ORM\OneToMany(targetEntity="MemberBillingDetail", mappedBy="memberbilling")
     */
    private $memberBillingDetail;

    /**
     * @ORM\Column(name="transaction_type", type="string", columnDefinition="enum('Payment', 'Refund', 'Cancellation')", nullable=false)
     */
    private $transactionType;
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
     * Set charge
     *
     * @param string $charge
     *
     * @return MemberBillingHistory
     */
    public function setCharge($charge)
    {
        $this->charge = $charge;

        return $this;
    }

    /**
     * Get charge
     *
     * @return string
     */
    public function getCharge()
    {
        return $this->charge;
    }

    /**
     * Set track
     *
     * @param string $track
     *
     * @return MemberBillingHistory
     */
    public function setTrack($track)
    {
        $this->track = $track;

        return $this;
    }

    /**
     * Get track
     *
     * @return string
     */
    public function getTrack()
    {
        return $this->track;
    }

    /**
     * Set card
     *
     * @param string $card
     *
     * @return MemberBillingHistory
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
     * Set lastFour
     *
     * @param string $lastFour
     *
     * @return MemberBillingHistory
     */
    public function setLastFour($lastFour)
    {
        $this->lastFour = $lastFour;

        return $this;
    }

    /**
     * Get lastFour
     *
     * @return string
     */
    public function getLastFour()
    {
        return $this->lastFour;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return MemberBillingHistory
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
     * Set vat
     *
     * @param string $vat
     *
     * @return MemberBillingHistory
     */
    public function setVat($vat)
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * Get vat
     *
     * @return string
     */
    public function getVat()
    {
        return $this->vat;
    }


    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return MemberBillingHistory
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return MemberBillingHistory
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
     * @return MemberBillingHistory
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
     * Set user
     *
     * @param User $user
     *
     * @return MemberBillingHistory
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
     * Set transactionResponse
     *
     * @param  $transactionResponse
     *
     * @return MemberBillingHistory
     */
    public function setTransactionResponse($transactionResponse)
    {
        $this->transactionResponse = $transactionResponse;

        return $this;
    }

    /**
     * Get transactionResponse
     *
     * @return
     */
    public function getTransactionResponse()
    {
        return $this->transactionResponse;
    }

    public function getImageName(){
        return $this->user->getCompanyLogo();
    }

    /**
     * Add memberBillingDetail
     *
     * @param MemberBillingDetail $memberBillingDetail
     *
     * @return MemberBillingHistory
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
     * Set transactionType
     *
     * @param string $transactionType
     *
     * @return MemberBillingHistory
     */
    public function setTransactionType($transactionType)
    {
        $this->transactionType = $transactionType;

        return $this;
    }

    /**
     * Get transactionType
     *
     * @return string
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    public function getScheduleDate()
    {
        return $this->mbhHelper->getScheduleDate($this->getId());
    }
}
