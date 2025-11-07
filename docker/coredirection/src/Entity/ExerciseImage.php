<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * ExerciseImage
 *
 * @ORM\Table(name="exercise_image")
 * @ORM\Entity(repositoryClass="App\Repository\ExerciseImageRepository")
 * @Vich\Uploadable
 */
class ExerciseImage extends BaseEntity
{

    public function __toString()
    {
        return "Exercise Image";
    }

    /**
     * @ORM\ManyToOne(targetEntity="Exercise", inversedBy="exerciseImage")
     * @ORM\JoinColumn(name="exercise_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $exercise;


    /**
     *
     * @Vich\UploadableField(mapping="exercise_images", fileNameProperty="name")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="sequence", type="integer")
     */
    private $sequence;


    /**
     * Set name
     *
     * @param string $name
     *
     * @return ExerciseImage
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
     * Set sequence
     *
     * @param integer $sequence
     *
     * @return ExerciseImage
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * Get sequence
     *
     * @return int
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * Set exercise
     *
     * @param Exercise $exercise
     *
     * @return ExerciseImage
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
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Article
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
     * @return File|null
     */
    public function getImageName()
    {
        return $this->name;
    }
}
