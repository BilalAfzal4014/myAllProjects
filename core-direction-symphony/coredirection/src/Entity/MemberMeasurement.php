<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * MemberMeasurement
 *
 * @ORM\Table(name="member_measurement")
 * @ORM\Entity(repositoryClass="App\Repository\MemberMeasurementRepository")
 * @Vich\Uploadable
 *
 */
class MemberMeasurement extends BaseEntity
{

    public function __toString()
    {
        return (string)$this->point;
    }

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="memberMeasurement")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
    private $user;


    /**
     * @var int
     *
     * @ORM\Column(name="point", type="integer")
     */
    private $point;

    /**
     * @var string
     *
     * @ORM\Column(name="weight", type="decimal", precision=10, scale=2)
     */
    private $weight;

    /**
     * @var string
     *
     * @ORM\Column(name="height", type="decimal", precision=10, scale=2)
     */
    private $height;

    /**
     * @var string
     *
     * @ORM\Column(name="waist", type="decimal", precision=10, scale=2)
     */
    private $waist;

    /**
     * @var string
     *
     * @ORM\Column(name="thighs", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $thighs;

    /**
     * @var string
     *
     * @ORM\Column(name="hips", type="decimal", precision=10, scale=2)
     */
    private $hips;

    /**
     * @var string
     *
     * @ORM\Column(name="chest", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $chest;

    /**
     * @var bool
     *
     * @ORM\Column(name="isCurrent", type="boolean", nullable=true)
     */
    private $isCurrent;


    /**
     *
     * @Vich\UploadableField(mapping="measurement_image", fileNameProperty="imageName")
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
     * @var string
     *
     * @ORM\Column(name="wrist", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $wrist;

    /**
     * @var string
     *
     * @ORM\Column(name="forearm", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $forearm;

    /**
     * Set point
     *
     * @param integer $point
     *
     * @return MemberMeasurement
     */
    public function setPoint($point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * Get point
     *
     * @return int
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * Set weight
     *
     * @param string $weight
     *
     * @return MemberMeasurement
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set height
     *
     * @param string $height
     *
     * @return MemberMeasurement
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return string
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set waist
     *
     * @param string $waist
     *
     * @return MemberMeasurement
     */
    public function setWaist($waist)
    {
        $this->waist = $waist;

        return $this;
    }

    /**
     * Get waist
     *
     * @return string
     */
    public function getWaist()
    {
        return $this->waist;
    }

    /**
     * Set thighs
     *
     * @param string $thighs
     *
     * @return MemberMeasurement
     */
    public function setThighs($thighs)
    {
        $this->thighs = $thighs;

        return $this;
    }

    /**
     * Get thighs
     *
     * @return string
     */
    public function getThighs()
    {
        return $this->thighs;
    }

    /**
     * Set hips
     *
     * @param string $hips
     *
     * @return MemberMeasurement
     */
    public function setHips($hips)
    {
        $this->hips = $hips;

        return $this;
    }

    /**
     * Get hips
     *
     * @return string
     */
    public function getHips()
    {
        return $this->hips;
    }

    /**
     * Set chest
     *
     * @param string $chest
     *
     * @return MemberMeasurement
     */
    public function setChest($chest)
    {
        $this->chest = $chest;

        return $this;
    }

    /**
     * Get chest
     *
     * @return string
     */
    public function getChest()
    {
        return $this->chest;
    }

    /**
     * Set isCurrent
     *
     * @param boolean $isCurrent
     *
     * @return MemberMeasurement
     */
    public function setIsCurrent($isCurrent)
    {
        $this->isCurrent = $isCurrent;

        return $this;
    }

    /**
     * Get isCurrent
     *
     * @return bool
     */
    public function getIsCurrent()
    {
        return $this->isCurrent;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return MemberMeasurement
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return MemberMeasurement
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
     * Set wrist
     *
     * @param string $wrist
     *
     * @return MemberMeasurement
     */
    public function setWrist($wrist)
    {
        $this->wrist = $wrist;

        return $this;
    }

    /**
     * Get wrist
     *
     * @return string
     */
    public function getWrist()
    {
        return $this->wrist;
    }

    /**
     * Set forearm
     *
     * @param string $forearm
     *
     * @return MemberMeasurement
     */
    public function setForearm($forearm)
    {
        $this->forearm = $forearm;

        return $this;
    }

    /**
     * Get forearm
     *
     * @return string
     */
    public function getForearm()
    {
        return $this->forearm;
    }
    public function getCompanyLogo()
    {

        if ($this->user) {
            return $this->user->getCompanyLogo();
        } else {
            return 'default_image.png';
        }
    }
}
