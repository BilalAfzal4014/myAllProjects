<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * WorkoutLevel
 *
 * @ORM\Table(name="workout_level")
 * @ORM\Entity(repositoryClass="App\Repository\WorkoutLevelRepository")
 * @Vich\Uploadable
 */
class WorkoutLevel extends BaseEntity
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
     * @var int
     *
     * @ORM\Column(name="point", type="integer")
     */
    private $point;

    /**
     * @ORM\OneToMany(targetEntity="Workout", mappedBy="level")
     */
    private $workout;


    /**
     *
     * @Vich\UploadableField(mapping="workout_level", fileNameProperty="imageName")
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
     * Set code
     *
     * @param string $code
     *
     * @return WorkoutLevel
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
     * @return WorkoutLevel
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
     * @return WorkoutLevel
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
     * Set point
     *
     * @param integer $point
     *
     * @return WorkoutLevel
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
     * @return mixed
     */
    public function getWorkout()
    {
        return $this->workout;
    }

    /**
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return WorkoutLevel
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
     * @return Article
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
            return '/images/workoutlevel/'.$this->imageName;
        } else {
            return '/icon/default_image.png';
        }
    }


    /**
     * Set nameAr
     *
     * @param string $nameAr
     *
     * @return WorkoutLevel
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
     * @return WorkoutLevel
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
     * Add workout
     *
     * @param Workout $workout
     *
     * @return WorkoutLevel
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
}
