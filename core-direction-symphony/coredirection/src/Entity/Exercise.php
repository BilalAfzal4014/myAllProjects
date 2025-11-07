<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Exercise
 *
 * @ORM\Table(name="exercise")
 * @ORM\Entity(repositoryClass="App\Repository\ExerciseRepository")
 * @Vich\Uploadable
 */
class Exercise extends BaseEntity
{

    public function __toString()
    {
        return (string)$this->code;
    }

    public function __construct()
    {
        $this->workoutexercise = new ArrayCollection();
        $this->exercise = new ArrayCollection();
        $this->exerciseLookup = new ArrayCollection();
    }

    private $exerciseHelper;

    public function exerciseHelper($exerciseHelper){
        $this->exerciseHelper = $exerciseHelper;
    }
    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="exercise")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


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
     *
     * @Vich\UploadableField(mapping="exercise_image", fileNameProperty="imageName")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @var string
     *
     * @ORM\Column(name="image_name", type="string", length=255, nullable=true)
     */
    private $imageName;

    /**
     * @var string
     *
     * @ORM\Column(name="video_name", type="string", length=255, nullable=true)
     */
    private $videoName;

    /**
     * @var string
     *
     * @ORM\Column(name="video_type", type="string", columnDefinition="enum('url', 'file')", nullable=true)
     */
    private $videoType;

    /**
     * @var int
     *
     * @ORM\Column(name="point", type="integer", nullable=true)
     */
    private $point;

    /**
     * @var int
     *
     * @ORM\Column(name="time", type="integer", nullable=true)
     */
    private $time;

    /**
     * @ORM\Column(name="description_ar", type="text", nullable=true)
     */
    private $descriptionAr;

    /**
     * @ORM\Column(name="name_ar", type="text", nullable=true)
     */
    private $nameAr;

    /**
     * @ORM\OneToMany(targetEntity="WorkoutExercise", mappedBy="exercise")
     */
    private $workoutexercise;

    /**
     * @ORM\OneToMany(targetEntity="ExerciseLookup", mappedBy="exercise")
     */

    private $exerciseLookup;

    /**
     * @ORM\OneToMany(targetEntity="ExerciseImage", mappedBy="exercise")
     */
    private $exercise;

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Exercise
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
     * @return Exercise
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
     * @return Exercise
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
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Exercise
     * @throws \Exception
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {

            $this->setUpdatedDate(new \DateTime());
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set imageName
     *
     * @param string $imageName
     *
     * @return Exercise
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get imageName
     *
     * @return string
     */
    public function getImageName()
    {

        if ($this->imageName) {
            return '/images/exercise/'.$this->imageName;
        } else {
            return '/icon/default_image.png';
        }

    }

    /**
     * Set videoName
     *
     * @param string $videoName
     *
     * @return Exercise
     */
    public function setVideoName($videoName)
    {
        $this->videoName = $videoName;

        return $this;
    }

    /**
     * Get videoName
     *
     * @return string
     */
    public function getVideoName()
    {
        return $this->videoName;
    }

    /**
     * Set videoType
     *
     * @param string $videoType
     *
     * @return Exercise
     */
    public function setVideoType($videoType)
    {
        $this->videoType = $videoType;

        return $this;
    }

    /**
     * Get videoType
     *
     * @return string
     */
    public function getVideoType()
    {
        return $this->videoType;
    }

    /**
     * Set point
     *
     * @param integer $point
     *
     * @return Exercise
     */
    public function setPoint($point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * Get point
     *
     * @return int
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * Set time
     *
     * @param integer $time
     *
     * @return Exercise
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return int
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Exercise
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
     * Add workoutexercise
     *
     * @param WorkoutExercise $workoutexercise
     *
     * @return Exercise
     */
    public function addWorkoutexercise(WorkoutExercise $workoutexercise)
    {
        $this->workoutexercise[] = $workoutexercise;

        return $this;
    }

    /**
     * Remove workoutexercise
     *
     * @param WorkoutExercise $workoutexercise
     */
    public function removeWorkoutexercise(WorkoutExercise $workoutexercise)
    {
        $this->workoutexercise->removeElement($workoutexercise);
    }

    /**
     * Get workoutexercise
     *
     * @return Collection
     */
    public function getWorkoutexercise()
    {
        return $this->workoutexercise;
    }

    /**
     * Set descriptionAr
     *
     * @param string $descriptionAr
     *
     * @return Exercise
     */
    public function setDescriptionAr($descriptionAr)
    {
        $this->descriptionAr = $descriptionAr;

        return $this;
    }

    /**
     * Get descriptionAr
     *
     * @return string
     */
    public function getDescriptionAr()
    {
        return $this->descriptionAr;
    }

    /**
     * Set nameAr
     *
     * @param string $nameAr
     *
     * @return Exercise
     */
    public function setNameAr($nameAr)
    {
        $this->nameAr = $nameAr;

        return $this;
    }

    /**
     * Get nameAr
     *
     * @return string
     */
    public function getNameAr()
    {
        return $this->nameAr;
    }

    /**
     * Add exerciseLookup
     *
     * @param ExerciseLookup $exerciseLookup
     *
     * @return Exercise
     */
    public function addExerciseLookup(ExerciseLookup $exerciseLookup)
    {
        $this->exerciseLookup[] = $exerciseLookup;

        return $this;
    }

    /**
     * Remove exerciseLookup
     *
     * @param ExerciseLookup $exerciseLookup
     */
    public function removeExerciseLookup(ExerciseLookup $exerciseLookup)
    {
        $this->exerciseLookup->removeElement($exerciseLookup);
    }

    /**
     * Get exerciseLookup
     *
     * @return Collection
     */
    public function getExerciseLookup()
    {
        return $this->exerciseLookup;
    }

    /**
     * Add exercise
     *
     * @param ExerciseImage $exercise
     *
     * @return Exercise
     */
    public function addExercise(ExerciseImage $exercise)
    {
        $this->exercise[] = $exercise;

        return $this;
    }

    /**
     * Remove exercise
     *
     * @param ExerciseImage $exercise
     */
    public function removeExercise(ExerciseImage $exercise)
    {
        $this->exercise->removeElement($exercise);
    }

    /**
     * Get exercise
     *
     * @return Collection
     */
    public function getExercise()
    {
        return $this->exercise;
    }

    public function getModel(){
        return $this->exerciseHelper->getExerciseModelByExerciseId($this->getId());
    }





}
