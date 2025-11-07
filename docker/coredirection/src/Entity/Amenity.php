<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Amenity
 *
 * @ORM\Table(name="amenity")
 * @ORM\Entity(repositoryClass="App\Repository\AmenityRepository")
 * @Vich\Uploadable()
 */
class Amenity extends BaseEntity
{

    public function __construct()
    {
        $this->facilityAmenity = new ArrayCollection();
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
     *
     * @Vich\UploadableField(mapping="amenity_image", fileNameProperty="imageName")
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
     * @ORM\OneToMany(targetEntity="FacilityAmenity", mappedBy="amenity")
     */
    private $facilityAmenity;

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Amenity
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
     * @return Amenity
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
     * @return Amenity
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
     * @return Amenity
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
            return $this->imageName;
        } else {
            return 'default_image.png';
        }
    }

    /**
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return CorporateDepartment
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
     * Add facilityAmenity
     *
     * @param FacilityAmenity $facilityAmenity
     *
     * @return Amenity
     */
    public function addFacilityAmenity(FacilityAmenity $facilityAmenity)
    {
        $this->facilityAmenity[] = $facilityAmenity;

        return $this;
    }

    /**
     * Remove facilityAmenity
     *
     * @param FacilityAmenity $facilityAmenity
     */
    public function removeFacilityAmenity(FacilityAmenity $facilityAmenity)
    {
        $this->facilityAmenity->removeElement($facilityAmenity);
    }

    /**
     * Get facilityAmenity
     *
     * @return Collection
     */
    public function getFacilityAmenity()
    {
        return $this->facilityAmenity;
    }

    
  
}
