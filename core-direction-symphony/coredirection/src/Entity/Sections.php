<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Sections
 *
 * @ORM\Table(name="sections")
 * @ORM\Entity(repositoryClass="App\Repository\SectionsRepository")
 * @Vich\Uploadable
 */
class Sections extends BaseEntity
{

    public function __toString()
    {
        return (string)$this->code;
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
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     *
     * @Vich\UploadableField(mapping="sections_image", fileNameProperty="bgImage")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @var string
     *
     * @ORM\Column(name="bg_image", type="string", length=255, nullable=true)
     */
    private $bgImage;

    /**
     * @var string
     *
     * @ORM\Column(name="bg_color", type="string", length=255)
     */
    private $bgColor;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", columnDefinition="enum('list', 'rect', 'box')")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="direction", type="string", columnDefinition="enum('horizontal', 'vertical')")
     */
    private $direction;

    /**
     * @var int
     *
     * @ORM\Column(name="section_order", type="integer")
     */
    private $sectionOrder;

    /**
     * @var string
     *
     * @ORM\Column(name="text_color", type="string", length=255)
     */
    private $textColor;

    /**
     *
     * @ORM\Column(name="name_ar", type="text", nullable=true)
     */
    private $nameAr;

    /**
     * @ORM\Column(name="cell_dimensions", type="string", nullable=true)
     */
    private $cellDimensions;

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
     * @return Sections
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
     * @return Sections
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
     * @return Sections
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
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Sections
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
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
     * @return Sections
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
     * Set bgImage
     *
     * @param string $bgImage
     *
     * @return Sections
     */
    public function setBgImage($bgImage)
    {
        $this->bgImage = $bgImage;

        return $this;
    }

    /**
     * Get bgImage
     *
     * @return string
     */
    public function getBgImage()
    {
        return $this->bgImage;
    }

    /**
     * Set bgColor
     *
     * @param string $bgColor
     *
     * @return Sections
     */
    public function setBgColor($bgColor)
    {
        $this->bgColor = $bgColor;

        return $this;
    }

    /**
     * Get bgColor
     *
     * @return string
     */
    public function getBgColor()
    {
        return $this->bgColor;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Sections
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set direction
     *
     * @param string $direction
     *
     * @return Sections
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * Get direction
     *
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * Set sectionOrder
     *
     * @param integer $sectionOrder
     *
     * @return Sections
     */
    public function setSectionOrder($sectionOrder)
    {
        $this->sectionOrder = $sectionOrder;

        return $this;
    }

    /**
     * Get sectionOrder
     *
     * @return int
     */
    public function getSectionOrder()
    {
        return $this->sectionOrder;
    }

    /**
     * Set textColor
     *
     * @param string $textColor
     *
     * @return Sections
     */
    public function setTextColor($textColor)
    {
        $this->textColor = $textColor;

        return $this;
    }

    /**
     * Get textColor
     *
     * @return string
     */
    public function getTextColor()
    {
        return $this->textColor;
    }


    /**
     * Set nameAr
     *
     * @param string $nameAr
     *
     * @return Sections
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
     * @return Sections
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

    public function getImageName(){

        if ($this->getBgImage()) {
            return '/images/sections_image/'.$this->getBgImage();
        } else {
            return '/icon/default_image.png';
        }
    }

    /**
     * Set cellDimensions
     *
     * @param string $cellDimensions
     *
     * @return Sections
     */
    public function setCellDimensions($cellDimensions)
    {
        $this->cellDimensions = $cellDimensions;

        return $this;
    }

    /**
     * Get cellDimensions
     *
     * @return string
     */
    public function getCellDimensions()
    {
        return $this->cellDimensions;
    }
}
