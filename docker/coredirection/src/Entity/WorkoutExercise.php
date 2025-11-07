<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WorkoutExercise
 *
 * @ORM\Table(name="workout_exercise")
 * @ORM\Entity(repositoryClass="App\Repository\WorkoutExerciseRepository")
 */
class WorkoutExercise extends BaseEntity
{

    /**
     *
     * @ORM\ManyToOne(targetEntity="Workout", inversedBy="workoutexercise")
     * @ORM\JoinColumn(name="workout_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $workout;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Exercise", inversedBy="workoutexercise")
     * @ORM\JoinColumn(name="exercise_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $exercise;


    /**
     * @ORM\Column(name="sequence", type="integer", nullable=true )
     */
    private $sequence;

    /**
     * @ORM\Column(name="duration", type="string", nullable=true )
     */
    private $duration;


    /**
     * Set workout
     *
     * @param Workout $workout
     *
     * @return WorkoutExercise
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

    /**
     * Set sequence
     *
     * @param integer $sequence
     *
     * @return WorkoutExercise
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * Get sequence
     *
     * @return integer
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return WorkoutExercise
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer
     */
    public function getDuration()
    {
        return $this->duration;
    }




    /**
     * Set exercise
     *
     * @param Exercise $exercise
     *
     * @return WorkoutExercise
     */
    public function setExercise(Exercise $exercise = null)
    {
        $this->exercise = $exercise;

        return $this;
    }

    /**
     * Get exercise
     *
     * @return Exercise
     */
    public function getExercise()
    {
        return $this->exercise;
    }
}
