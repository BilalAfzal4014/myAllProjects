<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProfileKey
 *
 * @ORM\Table(name="profile_key")
 * @ORM\Entity(repositoryClass="App\Repository\ProfileKeyRepository")
 */
class ProfileKey extends BaseEntity
{
    public function __toString()
    {
        return (string)$this->name;
    }
    
    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=100, unique=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=true, options={"default":0})
     */
    private $isActive = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expire_date", type="datetime", nullable=true)
     */
    private $expireDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_default", type="boolean", nullable=true, options={"default": 0})
     */
    private $isDefault = false;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="companyId")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $companyId;

    /**
     * @ORM\OneToMany(targetEntity="ProfileKeyArticles", mappedBy="profileKeyId")
     */
    private $profileKeyArticles;

    /**
     * @ORM\OneToMany(targetEntity="ProfileKeyWorkouts", mappedBy="profileKeyId")
     */
    private $profileWorkouts;

    /**
     * Set code
     *
     * @param string $code
     *
     * @return ProfileKey
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
     * @return ProfileKey
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
     * @return ProfileKey
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
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return ProfileKey
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set expireDate
     *
     * @param \DateTime $expireDate
     *
     * @return ProfileKey
     */
    public function setExpireDate($expireDate)
    {
        $this->expireDate = $expireDate;

        return $this;
    }

    /**
     * Get expireDate
     *
     * @return \DateTime
     */
    public function getExpireDate()
    {
        return $this->expireDate;
    }

    /**
     * Set isDefault
     *
     * @param boolean $isDefault
     *
     * @return ProfileKey
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * Get isDefault
     *
     * @return bool
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * Set companyId
     *
     * @param string $companyId
     *
     * @return ProfileKey
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;

        return $this;
    }

    /**
     * Get companyId
     *
     * @return string
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->profileKeyArticles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->profileWorkouts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add profileKeyArticle
     *
     * @param ProfileKeyArticles $profileKeyArticle
     *
     * @return ProfileKey
     */
    public function addProfileKeyArticle(ProfileKeyArticles $profileKeyArticle)
    {
        $this->profileKeyArticles[] = $profileKeyArticle;

        return $this;
    }

    /**
     * Remove profileKeyArticle
     *
     * @param ProfileKeyArticles $profileKeyArticle
     */
    public function removeProfileKeyArticle(ProfileKeyArticles $profileKeyArticle)
    {
        $this->profileKeyArticles->removeElement($profileKeyArticle);
    }

    /**
     * Get profileKeyArticles
     *
     * @return Collection
     */
    public function getProfileKeyArticles()
    {
        return $this->profileKeyArticles;
    }

    /**
     * Add profileWorkout
     *
     * @param ProfileKeyWorkouts $profileWorkout
     *
     * @return ProfileKey
     */
    public function addProfileWorkout(ProfileKeyWorkouts $profileWorkout)
    {
        $this->profileWorkouts[] = $profileWorkout;

        return $this;
    }

    /**
     * Remove profileWorkout
     *
     * @param ProfileKeyWorkouts $profileWorkout
     */
    public function removeProfileWorkout(ProfileKeyWorkouts $profileWorkout)
    {
        $this->profileWorkouts->removeElement($profileWorkout);
    }

    /**
     * Get profileWorkouts
     *
     * @return Collection
     */
    public function getProfileWorkouts()
    {
        return $this->profileWorkouts;
    }

    public function companyLogo(){
        return $this->companyId->getCompanyLogo();
    }
}
