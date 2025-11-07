<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Layout
 *
 * @ORM\Table(name="layout")
 * @ORM\Entity(repositoryClass="App\Repository\LayoutRepository")
 */
class Layout extends BaseEntity
{

    public function __construct()
    {
        $this->sections = new ArrayCollection();
        $this->appSettingLayout = new ArrayCollection();
    }

    public function __toString(){
        if($this->code) {
            return $this->code;
        }else{
            return "Layout";
        }
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
     * @var string
     *
     * @ORM\Column(name="bg_color", type="string", length=255)
     */
    private $bgColor;

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
     * @ORM\OneToMany(targetEntity="AppSettingLayout", mappedBy="layout")
     */
    private $appSettingLayout;

//    /**
//     * @ORM\ManyToOne(targetEntity="App\Entity\Layout")
//     */
//    private $parent;


    /**
     * Set code
     *
     * @param string $code
     *
     * @return Layout
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
     * @return Layout
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
     * @return Layout
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
     * Set bgColor
     *
     * @param string $bgColor
     *
     * @return Layout
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
     * Set nameAr
     *
     * @param string $nameAr
     *
     * @return Layout
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
     * @return Layout
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
     * Add appSettingLayout
     *
     * @param AppSettingLayout $appSettingLayout
     *
     * @return Layout
     */
    public function addAppSettingLayout(AppSettingLayout $appSettingLayout)
    {
        $this->appSettingLayout[] = $appSettingLayout;

        return $this;
    }

    /**
     * Remove appSettingLayout
     *
     * @param AppSettingLayout $appSettingLayout
     */
    public function removeAppSettingLayout(AppSettingLayout $appSettingLayout)
    {
        $this->appSettingLayout->removeElement($appSettingLayout);
    }

    /**
     * Get appSettingLayout
     *
     * @return Collection
     */
    public function getAppSettingLayout()
    {
        return $this->appSettingLayout;
    }

//    /**
//     * @return mixed
//     */
//    public function getParent()
//    {
//        return $this->parent;
//    }
//
//    /**
//     * @param mixed $parent
//     */
//    public function setParent($parent)
//    {
//        $this->parent = $parent;
//    }


}
