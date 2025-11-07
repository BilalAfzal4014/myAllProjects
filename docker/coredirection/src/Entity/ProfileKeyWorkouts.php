<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProfileKeyWorkouts
 *
 * @ORM\Table(name="profile_key_workouts")
 * @ORM\Entity(repositoryClass="App\Repository\ProfileKeyWorkoutsRepository")
 */
class ProfileKeyWorkouts extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="ProfileKey", inversedBy="profileWorkouts")
     * @ORM\JoinColumn(name="profile_key_id", referencedColumnName="id")
     */
    private $profileKeyId;

    /**
     * @ORM\ManyToOne(targetEntity="Workout", inversedBy="profileWorkouts")
     * @ORM\JoinColumn(name="workout_id", referencedColumnName="id")
     */
    private $workoutId;

    /**
     * Set profileKeyId
     *
     * @param string $profileKeyId
     *
     * @return ProfileKeyWorkouts
     */
    public function setProfileKeyId($profileKeyId)
    {
        $this->profileKeyId = $profileKeyId;

        return $this;
    }

    /**
     * Get profileKeyId
     *
     * @return string
     */
    public function getProfileKeyId()
    {
        return $this->profileKeyId;
    }

    /**
     * Set workoutId
     *
     * @param string $workoutId
     *
     * @return ProfileKeyWorkouts
     */
    public function setWorkoutId($workoutId)
    {
        $this->workoutId = $workoutId;

        return $this;
    }

    /**
     * Get workoutId
     *
     * @return string
     */
    public function getWorkoutId()
    {
        return $this->workoutId;
    }


}
