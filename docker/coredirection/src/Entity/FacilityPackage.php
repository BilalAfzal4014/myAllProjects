<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * FacilityPackage
 *
 * @ORM\Table(name="facility_package")
 * @ORM\Entity(repositoryClass="App\Repository\FacilityPackageRepository")
 */
class FacilityPackage extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="facilityPackage")
     * @ORM\JoinColumn(name="facility_id", referencedColumnName="id")
     */
    private $facility;


    /**
     * @ORM\ManyToOne(targetEntity="Package", inversedBy="facilityPackage")
     * @ORM\JoinColumn(name="package_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $package;



    /**
     * Set facility
     *
     * @param User $facility
     *
     * @return FacilityPackage
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
     * Set package
     *
     * @param Package $package
     *
     * @return FacilityPackage
     */
    public function setPackage(Package $package = null)
    {
        $this->package = $package;

        return $this;
    }

    /**
     * Get package
     *
     * @return Package
     */
    public function getPackage()
    {
        return $this->package;
    }
    public function getImageName(){
        return $this->facility->getCompanyLogo();
    }
}
