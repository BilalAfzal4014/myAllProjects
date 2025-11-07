<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * MemberWorkout
 *
 * @ORM\Table(name="member_workout")
 * @ORM\Entity(repositoryClass="App\Repository\MemberWorkoutRepository")
 */
class MemberWorkout extends BaseEntity
{

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="memberWorkout")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
    private $member;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Workout", inversedBy="memberWorkout")
     * @ORM\JoinColumn(name="workout_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $workout;

    /**
     * @ORM\ManyToOne(targetEntity="CorporateKey", inversedBy="memberWorkout")
     * @ORM\JoinColumn(name="key_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $key;


    /**
     * @var bool
     *
     * @ORM\Column(name="is_favourite", type="boolean", nullable=true)
     */
    private $isFavourite;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_history", type="boolean", options={"default":0})
     */
    private $isHistory = false;

    /**
     * @return boolean
     */
    public function isIsHistory()
    {
        return $this->isHistory;
    }

    /**
     * @param boolean $isHistory
     */
    public function setIsHistory($isHistory)
    {
        $this->isHistory = $isHistory;
    }


    /**
     * Set isFavourite
     *
     * @param boolean $isFavourite
     *
     * @return MemberWorkout
     */
    public function setIsFavourite($isFavourite)
    {
        $this->isFavourite = $isFavourite;

        return $this;
    }

    /**
     * Get isFavourite
     *
     * @return bool
     */
    public function getIsFavourite()
    {
        return $this->isFavourite;
    }

    /**
     * Set member
     *
     * @param User $member
     *
     * @return MemberWorkout
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
     * Set workout
     *
     * @param Workout $workout
     *
     * @return MemberWorkout
     */
    public function setWorkout(Workout $workout = null)
    {
        $this->workout = $workout;

        return $this;
    }

    /**
     * Get workout
     *
     * @return Workout
     */
    public function getWorkout()
    {
        return $this->workout;
    }

    public function getImageName(){

        return $this->member->getCompanyLogo();
    }

    /**
     * Get isHistory
     *
     * @return boolean
     */
    public function getIsHistory()
    {
        return $this->isHistory;
    }

    /**
     * Set key
     *
     * @param CorporateKey $key
     *
     * @return MemberWorkout
     */
    public function setKey(CorporateKey $key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return CorporateKey
     */
    public function getKey()
    {
        return $this->key;
    }
}
