<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * CorporateDepartment
 *
 * @ORM\Table(name="corporate_department")
 * @ORM\Entity(repositoryClass="App\Repository\CorporateDepartmentRepository")
 * @Vich\Uploadable()
 */
class CorporateDepartment extends BaseEntity
{

    public function __construct()
    {
        $this->depts = new ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this->name;
    }

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="corporateDepartment")
     * @ORM\JoinColumn(name="corporate_id", referencedColumnName="id")
     */

    private $corporateUser;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=25)
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
     * @Vich\UploadableField(mapping="department_image", fileNameProperty="imageName")
     *
     * @var File
     */
    private $profileImage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $imageName;

    /**
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return CorporateDepartment
     */
    public function setProfileImage(File $image = null)
    {
        $this->profileImage = $image;
        if ($image) {

            $this->setUpdatedDate(new \DateTime());
        }


        return $this;
    }

    /**
     * @return File|null
     */
    public function getProfileImage()
    {
        return $this->profileImage;
    }

    /**
     * @param string $imageName
     *
     * @return CorporateDepartment
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return string|null
     */
//    public function getImageName()
//    {
//        if ($this->imageName) {
//            return $this->imageName;
//        } else {
//            return 'default_image.png';
//        }
//    }



    /**
     * Set corporate
     *
     * @param integer $corporate
     * @return CorporateDepartment
     */
    public function setCorporateUser($corporate)
    {
        $this->corporateUser = $corporate;

        return $this;
    }

    /**
     * Get corporate
     *
     * @return integer
     */
    public function getCorporateUser()
    {
        return $this->corporateUser;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return CorporateDepartment
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
     * @return CorporateDepartment
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
     * @return CorporateDepartment
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


    public function getImageName(){
        if($this->corporateUser) {
            return $this->corporateUser->getCompanyLogo();
        }else{
            return '';
        }
    }
}
