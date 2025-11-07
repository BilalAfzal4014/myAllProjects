<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * workoutType
 *
 * @ORM\Table(name="workout_type")
 * @ORM\Entity(repositoryClass="App\Repository\workoutTypeRepository")
 * @Vich\Uploadable
 */
class WorkoutType extends BaseEntity
{

    public function __construct()
    {
        $this->workout = new ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this->code;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=100)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;


    /**
     * @var string
     *
     * @ORM\Column(name="sequence", type="integer", nullable=false)
     */
    private $sequence;

    /**
     * @ORM\OneToMany(targetEntity="Workout", mappedBy="workoutType")
     */
    private $workout;

    /**
     *
     * @Vich\UploadableField(mapping="workout_type", fileNameProperty="imageName")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(name="image_name", type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $imageName;

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
     * Set name
     *
     * @param string $name
     *
     * @return workoutType
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
     * Set code
     *
     * @param string $code
     *
     * @return workoutType
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
     * Set description
     *
     * @param string $description
     *
     * @return workoutType
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
     * Add workout
     *
     * @param workout $workout
     *
     * @return workoutType
     */
    public function addWorkout(Workout $workout)
    {
        $this->workout[] = $workout;

        return $this;
    }

    /**
     * Remove workout
     *
     * @param Workout $workout
     */
    public function removeWorkout(Workout $workout)
    {
        $this->workout->removeElement($workout);
    }

    /**
     * Get workout
     *
     * @return Collection
     */
    public function getWorkout()
    {
        return $this->workout;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return WorkoutType
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
     * @param string $imageName
     *
     * @return WorkoutType
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageName()
    {
        if ($this->imageName) {
            return '/images/workouttype/'.$this->imageName;
        } else {
            return '/icon/default_image.png';
        }
    }


    /**
     * Set nameAr
     *
     * @param string $nameAr
     *
     * @return WorkoutType
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
     * @return WorkoutType
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
     * @return string
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * @param string $sequence
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;
    }




}
