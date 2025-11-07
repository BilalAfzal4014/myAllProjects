<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * workout
 *
 * @ORM\Table(name="workout")
 * @ORM\Entity(repositoryClass="App\Repository\workoutRepository")
 * @Vich\Uploadable()
 */
class Workout extends BaseEntity
{

    public function __construct()
    {
        $this->workoutexercise = new ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this->code;
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="workout")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     *
     * @ORM\ManyToOne(targetEntity="WorkoutType", inversedBy="workout")
     * @ORM\JoinColumn(name="workout_type", referencedColumnName="id", onDelete="CASCADE")
     */
    private $workoutType;

    /**
     * @ORM\ManyToOne(targetEntity="WorkoutLevel", inversedBy="workout")
     * @ORM\JoinColumn(name="level_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $level;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=100)
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
     *
     * @Vich\UploadableField(mapping="workout_image", fileNameProperty="imageName")
     *
     * @var File
     */
    private $ImageFile;

    /**
     * @var string
     *
     * @ORM\Column(name="image_name", type="string", length=255, nullable=true)
     */
    private $imageName;


    /**
     * @var string
     *
     * @ORM\Column(name="background_music", type="string", length=100, nullable=true)
     */
    private $backgroundMusic;

    /**
     * @var int
     *
     * @ORM\Column(name="duration", type="string", nullable=true)
     */
    private $duration;

    /**
     * @var int
     *
     * @ORM\Column(name="point", type="integer", nullable=true)
     */
    private $point;
    /**
     * @ORM\OneToMany(targetEntity="WorkoutExercise", mappedBy="workout")
     */
    private $workoutexercise;

    /**
     * @ORM\OneToMany(targetEntity="MemberWorkout", mappedBy="workout")
     */
    private $memberWorkout;

    /**
     * @ORM\Column(name="is_recommended", type="boolean", nullable=true)
     */

    private $isRecommended;

    /**
     * @ORM\OneToMany(targetEntity="ProfileKeyWorkouts", mappedBy="workoutId")
     */
    private $profileWorkouts;

    /**
     *
     * @ORM\Column(name="name_ar", type="text", nullable=true)
     */
    private $nameAr;


    /**
     * @var string
     *
     * @ORM\Column(name="description_ar", type="text", nullable=true)
     */
    private $descriptionAr;

    /**
     * @return mixed
     */
    public function getIsRecommended()
    {
        return $this->isRecommended;
    }

    /**
     * @param mixed $isRecommended
     */
    public function setIsRecommended($isRecommended)
    {
        $this->isRecommended = $isRecommended;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return workout
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
     * @return workout
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
     * @return workout
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
     * Set imageName
     *
     * @param string $imageName
     *
     * @return workout
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
            return '/images/workout/'.$this->imageName;
        } else {
            return '/icon/default_image.png';
        }
    }


    /**
     * Set backgroundMusic
     *
     * @param string $backgroundMusic
     *
     * @return workout
     */
    public function setBackgroundMusic($backgroundMusic)
    {
        $this->backgroundMusic = $backgroundMusic;

        return $this;
    }

    /**
     * Get backgroundMusic
     *
     * @return string
     */
    public function getBackgroundMusic()
    {
        return $this->backgroundMusic;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return workout
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Workout
     * @throws \Exception
     */
    public function setImageFile(File $image = null)
    {
        $this->ImageFile = $image;

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
        return $this->ImageFile;
    }

    /**
     * Set point
     *
     * @param integer $point
     *
     * @return workout
     */
    public function setPoint($point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * Get point
     *
     * @return integer
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return workout
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
     * Set workoutType
     *
     * @param workoutType $workoutType
     *
     * @return workout
     */
    public function setWorkoutType(WorkoutType $workoutType = null)
    {
        $this->workoutType = $workoutType;

        return $this;
    }

    /**
     * Get workoutType
     *
     * @return workoutType
     */
    public function getWorkoutType()
    {
        return $this->workoutType;
    }

    /**
     * Add workoutexercise
     *
     * @param WorkoutExercise $workoutexercise
     *
     * @return Workout
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
     * Add memberWorkout
     *
     * @param MemberWorkout $memberWorkout
     *
     * @return Workout
     */
    public function addMemberWorkout(MemberWorkout $memberWorkout)
    {
        $this->memberWorkout[] = $memberWorkout;

        return $this;
    }

    /**
     * Remove memberWorkout
     *
     * @param MemberWorkout $memberWorkout
     */
    public function removeMemberWorkout(MemberWorkout $memberWorkout)
    {
        $this->memberWorkout->removeElement($memberWorkout);
    }

    /**
     * Get memberWorkout
     *
     * @return Collection
     */
    public function getMemberWorkout()
    {
        return $this->memberWorkout;
    }

    /**
     * Set level
     *
     * @param WorkoutLevel $level
     *
     * @return Workout
     */
    public function setLevel(WorkoutLevel $level = null)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return WorkoutLevel
     */
    public function getLevel()
    {
        return $this->level;
    }

    

    /**
     * Set nameAr
     *
     * @param string $nameAr
     *
     * @return Workout
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
     * Set descriptionAr
     *
     * @param string $descriptionAr
     *
     * @return Workout
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


    public function addProfileWorkout(ProfileKeyWorkouts $profileWorkout)
    {
        $this->profileWorkouts[] = $profileWorkout;

        return $this;
    }


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


//    public function getDefaultProfilesForWorkouts()
//    {
//        global $kernel;
//        $em = $kernel->getContainer()->get('doctrine')->getManager();
//        /**
//         * @var $em EntityManager
//         */
//        $profiles = $em->getRepository('CoredirectionBundle:ProfileKey')->findBy(array(
//            'isDefault' => 1,
//            'isActive' => 1
//        ));
//        $choice = [];
//        foreach ($profiles as $profile){
//            $choice[$profile->getId()] = $profile->getName();
//        }
////        dump($choice);die;
//        return $profiles;
//    }






}
