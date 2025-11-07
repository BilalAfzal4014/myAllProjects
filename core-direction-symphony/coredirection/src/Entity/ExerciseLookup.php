<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ExerciseLookup
 *
 * @ORM\Table(name="exercise_lookup")
 * @ORM\Entity(repositoryClass="App\Repository\ExerciseLookupRepository")
 */
class ExerciseLookup extends BaseEntity
{

   
    /**
     *
     * @ORM\ManyToOne(targetEntity="Exercise", inversedBy="exerciseLookup")
     * @ORM\JoinColumn(name="exercise_id", referencedColumnName="id" , onDelete="CASCADE")
     */
    private $exercise;

    /**
     * @ORM\ManyToOne(targetEntity="BaseLookup", inversedBy="exercise_lookup")
     * @ORM\JoinColumn(name="lookup_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $baseLookup;


    /**
     * Set exercise
     *
     * @param Exercise $exercise
     *
     * @return ExerciseLookup
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


    /**
     * Set baseLookup
     *
     * @param BaseLookup $baseLookup
     *
     * @return ExerciseLookup
     */
    public function setBaseLookup(BaseLookup $baseLookup = null)
    {
        $this->baseLookup = $baseLookup;

        return $this;
    }

    /**
     * Get baseLookup
     *
     * @return BaseLookup
     */
    public function getBaseLookup()
    {
        return $this->baseLookup;
    }
}
