<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * MemberPromotionClicks
 *
 * @ORM\Table(name="member_promotion_clicks")
 * @ORM\Entity(repositoryClass="App\Repository\MemberPromotionClicksRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MemberPromotionClicks
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
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="MemberPromotionClicks")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id", nullable=false)
     */
    private $member;

    /**
     * @ORM\ManyToOne(targetEntity="Promotions", inversedBy="MemberPromotionClicks")
     * @ORM\JoinColumn(name="promotion_id", referencedColumnName="id", onDelete="CASCADE" , nullable=false)
     */
    private $promotion;
    /**
     * @var int
     *
     * @ORM\Column(name="clicks", type="bigint", options={"default":0})
     */
    private $clicks;

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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set clicks
     *
     * @param integer $clicks
     *
     * @return MemberPromotionClicks
     */
    public function setClicks($clicks)
    {
        $this->clicks = $clicks;

        return $this;
    }

    /**
     * Get clicks
     *
     * @return int
     */
    public function getClicks()
    {
        return $this->clicks;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return MemberPromotionClicks
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
     * @return MemberPromotionClicks
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
     * Set member
     *
     * @param User $member
     *
     * @return MemberPromotionClicks
     */
    public function setMember(User $member)
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
     * Set promotion
     *
     * @param Promotions $promotion
     *
     * @return MemberPromotionClicks
     */
    public function setPromotion(Promotions $promotion)
    {
        $this->promotion = $promotion;

        return $this;
    }

    /**
     * Get promotion
     *
     * @return Promotions
     */
    public function getPromotion()
    {
        return $this->promotion;
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
     * Set code
     *
     * @param string $code
     *
     * @return MemberPromotionClicks
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
     * @return MemberPromotionClicks
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
     * @return MemberPromotionClicks
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
}
