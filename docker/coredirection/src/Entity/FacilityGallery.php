<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use PhpCollection\Sequence;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * FacilityGallery
 *
 * @ORM\Table(name="facility_gallery")
 * @ORM\Entity(repositoryClass="App\Repository\FacilityGalleryRepository")
 * @Vich\Uploadable
 */
class FacilityGallery extends BaseEntity
{


    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="facilityGallery")
     * @ORM\JoinColumn(name="facility_id", referencedColumnName="id")
     */
    private $facility;

    /**
     *
     * @Vich\UploadableField(mapping="facility_gallery", fileNameProperty="imageName")
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
     * @var int
     *
     * @ORM\Column(name="sequence", type="integer", nullable=true)
     */
    private $sequence;

    /**
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return FacilityGallery
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
            return $this->imageName;
        } else {
            return 'default_image.png';
        }
    }



    /**
     * Set facility
     *
     * @param User $facility
     *
     * @return FacilityGallery
     */
    public function setFacility(User $facility = null)
    {
        $this->facility = $facility;

        return $this;
    }

    /**
     * Get facility
     *
     * @return User
     */
    public function getFacility()
    {
        return $this->facility;
    }

    /**
     * Set sequence
     *
     * @param integer $sequence
     *
     * @return Sequence
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
}
