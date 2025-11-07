<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * FacilityAmenity
 *
 * @ORM\Table(name="facility_amenity")
 * @ORM\Entity(repositoryClass="App\Repository\FacilityAmenityRepository")
 */
class FacilityAmenity extends BaseEntity
{

   

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="amenity")
     * @ORM\JoinColumn(name="facility_id", referencedColumnName="id")
     */
    private $facility;

    /**
     * @ORM\ManyToOne(targetEntity="Amenity", inversedBy="facilityAmenity")
     * @ORM\JoinColumn(name="amenity_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $amenity;



    /**
     * Set facility
     *
     * @param User $facility
     *
     * @return FacilityAmenity
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
     * Set amenity
     *
     * @param Amenity $amenity
     *
     * @return FacilityAmenity
     */
    public function setAmenity(Amenity $amenity = null)
    {
        $this->amenity = $amenity;

        return $this;
    }

    /**
     * Get amenity
     *
     * @return Amenity
     */
    public function getAmenity()
    {
        return $this->amenity;
    }

    public function getImageName(){
        if ($this->facility) {
            return $this->facility->getCompanyLogo();
        }else{
            return false;
        }
    }
}
