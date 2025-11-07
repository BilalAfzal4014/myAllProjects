<?php

namespace App\Entity;

use App\Entity\BaseEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Activity
 *
 * @ORM\Table(name="activity")
 * @ORM\Entity(repositoryClass="App\Repository\ActivityRepository")
 * @Vich\Uploadable
 * @UniqueEntity(
 *     fields={"code"},
 *     errorPath="code",
 *     message="This code is already exist."
 * )
 */
class Activity extends BaseEntity
{

    public function __construct()
    {
        $this->activitySchedule = new ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this->name;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=50, unique=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
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
     * @Vich\UploadableField(mapping="activity_image", fileNameProperty="imageName")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $imageName;

    /**
     * @ORM\OneToMany(targetEntity="ActivitySchedule", mappedBy="activity")
     */
    private $activitySchedule;

    /**
     * @ORM\ManyToOne(targetEntity="ActivityType", inversedBy="activity")
     * @ORM\JoinColumn(name="activity_type_id", referencedColumnName="id")
     */
    private $activityType;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ManualCheckinHistory",mappedBy="activity")
     */
    private $manualCheckInhistory;
    /**
     * Set name
     *
     * @param string $name
     *
     * @return Activity
     */
    public function setName($name)
    {
        $this->name = ucwords( strtolower($name));

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
     * @return Activity
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
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Activity
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
     * @return Activity
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
            return '/images/activity/'.$this->imageName;
        } else {
            return '/icon/default_image.png';
        }
    }

    /**
     * Add activitySchedule
     *
     * @param \App\Entity\ActivitySchedule $activitySchedule
     *
     * @return Activity
     */
    public function addActivitySchedule(\App\Entity\ActivitySchedule $activitySchedule)
    {
        $this->activitySchedule[] = $activitySchedule;

        return $this;
    }

    /**
     * Remove activitySchedule
     *
     * @param \App\Entity\ActivitySchedule $activitySchedule
     */
    public function removeActivitySchedule(\App\Entity\ActivitySchedule $activitySchedule)
    {
        $this->activitySchedule->removeElement($activitySchedule);
    }

    /**
     * Get activitySchedule
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActivitySchedule()
    {
        return $this->activitySchedule;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Activity
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
     * Set activityType
     *
     * @param \App\Entity\ActivityType $activityType
     *
     * @return Activity
     */
    public function setActivityType(\App\Entity\ActivityType $activityType = null)
    {
        $this->activityType = $activityType;

        return $this;
    }

    /**
     * Get activityType
     *
     * @return \App\Entity\ActivityType
     */
    public function getActivityType()
    {
        return $this->activityType;
    }

    /**
     * @return mixed
     */
    public function getManualCheckInhistory()
    {
        return $this->manualCheckInhistory;
    }

    /**
     * @param mixed $manualCheckInhistory
     */
    public function setManualCheckInhistory($manualCheckInhistory)
    {
        $this->manualCheckInhistory = $manualCheckInhistory;
    }


}
