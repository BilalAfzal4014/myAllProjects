<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Promotions
 *
 * @ORM\Table(name="promotions")
 * @ORM\Entity(repositoryClass="App\Repository\PromotionsRepository")
 * @Vich\Uploadable
 * @Assert\Callback(methods={"validate"})
 */
class Promotions extends BaseEntity
{

    public function __construct()
    {
        $this->MemberPromotionClicks = new ArrayCollection();
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
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255,nullable=true)
     */
    private $url;

    /**
     *
     * @Vich\UploadableField(mapping="promotion", fileNameProperty="imageName")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $imageName;

    /**
     * @var int
     *
     * @ORM\Column(name="ordering", type="integer", options={"default":0})
     */
    private $ordering;

    /**
     * @ORM\OneToMany(targetEntity="MemberPromotionClicks", mappedBy="promotions")
     */
    private $MemberPromotionClicks;

    /**
     * @ORM\Column(type="string")
     */
    private $promotionType;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ActivitySchedule")
     */
    private $activitySchedule;



    /**
     * Set description
     *
     * @param string $description
     *
     * @return Promotions
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
     * Set url
     *
     * @param string $url
     *
     * @return Promotions
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set imageName
     *
     * @param string $imageName
     *
     * @return Promotions
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
            return '/images/promotion/'.$this->imageName;
        } else {
            return '/icon/default_image.png';
        }
    }

    /**
     * Set ordering
     *
     * @param integer $ordering
     *
     * @return Promotions
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;

        return $this;
    }

    /**
     * Get ordering
     *
     * @return int
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Promotions
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
     * Set code
     *
     * @param string $code
     *
     * @return Promotions
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
     * @return Promotions
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
     * Add memberPromotionClick
     *
     * @param MemberPromotionClicks $memberPromotionClick
     *
     * @return Promotions
     */
    public function addMemberPromotionClick(MemberPromotionClicks $memberPromotionClick)
    {
        $this->MemberPromotionClicks[] = $memberPromotionClick;

        return $this;
    }

    /**
     * Remove memberPromotionClick
     *
     * @param MemberPromotionClicks $memberPromotionClick
     */
    public function removeMemberPromotionClick(MemberPromotionClicks $memberPromotionClick)
    {
        $this->MemberPromotionClicks->removeElement($memberPromotionClick);
    }

    /**
     * Get memberPromotionClicks
     *
     * @return Collection
     */
    public function getMemberPromotionClicks()
    {
        return $this->MemberPromotionClicks;
    }

    public function __toString()
    {
        if ($this->name) {
            return $this->name;
        } else {
            return 'New Promotion';
        }
    }

    /**
     * @return mixed
     */
    public function getPromotionType()
    {
        return $this->promotionType;
    }

    /**
     * @param mixed $promotionType
     */
    public function setPromotionType($promotionType)
    {
        $this->promotionType = $promotionType;
    }

    /**
     * @return mixed
     */
    public function getActivitySchedule()
    {
        return $this->activitySchedule;
    }

    /**
     * @param mixed $activitySchedule
     */
    public function setActivitySchedule($activitySchedule)
    {
        $this->activitySchedule = $activitySchedule;
    }


    /**
     * @param ExecutionContextInterface $context
     * @return bool
     */
    public function validate(ExecutionContextInterface $context)
    {
        if ($this->imageFile) {
            if (!in_array($this->imageFile->getMimeType(), array(
                'image/jpeg',
                'image/bmp',
                'image/png',
//            'video/mp4',
//            'video/quicktime',
//            'video/avi',
            ))) {
                $context
                    ->buildViolation('Wrong file type. Only jpg,bmp and png are allowed')
                    ->atPath('fileName')
                    ->addViolation();
            }
        } else {
            return true;
        }

    }






}
