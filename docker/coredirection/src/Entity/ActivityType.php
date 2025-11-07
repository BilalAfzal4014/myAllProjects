<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;

use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;

/**
 * ActivityType
 *
 * @ORM\Table(name="activity_type")
 * @ORM\Entity(repositoryClass="App\Repository\ActivityTypeRepository")
 * @Vich\Uploadable
 */
class ActivityType extends BaseEntity
{

    public function __toString()
    {
        return (string)$this->name;
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
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="activityType")
     */
    private $activity;
    /**
     * Set code
     *
     * @param string $code
     *
     * @return ActivityType
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
     * @return ActivityType
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
     * @return ActivityType
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
     * Constructor
     */
    public function __construct()
    {
        $this->activity = new ArrayCollection();
    }

    /**
     * Add activity
     *
     * @param Activity $activity
     *
     * @return ActivityType
     */
    public function addActivity(Activity $activity)
    {
        $this->activity[] = $activity;

        return $this;
    }

    /**
     * Remove activity
     *
     * @param Activity $activity
     */
    public function removeActivity(Activity $activity)
    {
        $this->activity->removeElement($activity);
    }

    /**
     * Get activity
     *
     * @return Collection
     */
    public function getActivity()
    {
        return $this->activity;
    }


    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {

            $this->setUpdatedDate(new \DateTime());
        }

        return $this;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }


    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }


    public function getImageName()
    {
        if ($this->imageName) {
            return '/images/activity/'.$this->imageName;
        } else {
            return '/icon/default_image.png';
        }
    }

}
