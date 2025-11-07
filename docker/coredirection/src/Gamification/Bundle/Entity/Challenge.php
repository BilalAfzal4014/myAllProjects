<?php
/**
 * Created by PhpStorm.
 * User: omair
 * Date: 29/11/18
 * Time: 10:14 AM
 */

namespace App\Gamification\Bundle\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use JMS\Serializer\Annotation as Serializer;
/**
 * @ORM\Table(name="challenge")
 * @ORM\Entity(repositoryClass="App\Gamification\Bundle\Repository\ChallengeRepository")
 * @Vich\Uploadable
 * @Serializer\ExclusionPolicy("all")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"code"},
 *     errorPath="code",
 *     message="This code is already exist."
 * )
 */
class Challenge extends BaseEntity
{

    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="gamification", fileNameProperty="logoName", size="logoSize")
     *
     * @var File
     */
    private $logoFile;

    /**
     * @ORM\Column(type="string", length=255,name="logo_name")
     *
     * @var string
     */
    private $logoName;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Expose()
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start_date;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Expression(
     *     "this.getStartDate() <= this.getEndDate()",
     *     message="The end date must be after the start date")
     */
    private $end_date;

    private $memberInvites;

    /**
     * @ORM\Column(type="boolean",name="is_active")
     */
    private $isActive;
    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User")
     */
    private $company;
    /**
     * @ORM\Column(type="string",unique=true)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="App\Gamification\Bundle\Entity\ChallengeAction",mappedBy="challenge")
     */
    private $challengeAction;
    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * @param mixed $start_date
     */
    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * @param mixed $end_date
     */
    public function setEndDate($end_date)
    {
        $this->end_date = $end_date;
    }



    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    public function __toString()
    {

        return (string)$this->title;
        // TODO: Implement __toString() method.
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return File
     */
    public function getLogoFile()
    {
        return $this->logoFile;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $imageFile
     * @throws \Exception
     */
    public function setLogoFile(File $logoFile = null)
    {
        $this->logoFile = $logoFile;

        if (null !== $logoFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->setUpdatedDate( new \DateTimeImmutable());
        }
    }

    /**
     * @return string
     */
    public function getLogoName()
    {
        return $this->logoName;
    }

    /**
     * @param string $logoName
     */
    public function setLogoName($logoName)
    {
        $this->logoName = $logoName;
    }


    /**
     * @return mixed
     */
    public function getisActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getChallengeAction()
    {
        return $this->challengeAction;
    }

    /**
     * @param mixed $challengeAction
     */
    public function setChallengeAction($challengeAction)
    {
        $this->challengeAction = $challengeAction;
    }

    /**
     * @return mixed
     */
    public function getMemberInvites()
    {
        return $this->memberInvites;
    }

    /**
     * @param mixed $memberInvites
     */
    public function setMemberInvites($memberInvites)
    {
        $this->memberInvites = $memberInvites;
    }

    /**
     * @Serializer\SerializedName("id")
     * @Serializer\Expose()
     *@Serializer\VirtualProperty()
     */
    public function getIdForApi()
    {
        return $this->getId();
    }

    /**
     * @Serializer\SerializedName("logo")
     * @Serializer\Expose()
     *@Serializer\VirtualProperty()
     */
    public function getLogoForApi()
    {

        if($this->getStartDate() < new \DateTime()) {
            return '/images/challenges/' . $this->getLogoName();
        }else{

            return $this->getCompanyLogoForApi();
        }
    }

    /**
     * @Serializer\SerializedName("title")
     * @Serializer\Expose()
     *@Serializer\VirtualProperty()
     */
    public function getTitleForApi()
    {

        if($this->getStartDate() < new \DateTime()) {
            return $this->getTitle();
        }else{

            /**
             * @var  $company User
             */
            $company = $this->company;
            return $company->getCompanyName();
        }
    }

    /**
     * @Serializer\SerializedName("company_logo")
     * @Serializer\Expose()
     *@Serializer\VirtualProperty()
     */
    public function getCompanyLogoForApi()
    {
        /**
         * @var  $company User
         */
        $company = $this->company;
        return $company->getCompanyLogo();
    }

    /**
     * @Serializer\SerializedName("start_date")
     * @Serializer\Expose()
     *@Serializer\VirtualProperty()
     */
    public function getStartDateApi()
    {
        return $this->getStartDate()->format('jS F Y');
    }

    /**
     * @Serializer\SerializedName("end_date")
     * @Serializer\Expose()
     *@Serializer\VirtualProperty()
     */
    public function getEndDateApi()
    {
            return $this->getEndDate()->format('jS F Y');
    }

    /**
     * @Serializer\SerializedName("day_total")
     * @Serializer\Expose()
     *@Serializer\VirtualProperty()
     */
    public function getTotalDays()
    {

        $date1=date_create($this->end_date->format('Y-m-d'));
        $date2=date_create($this->start_date->format('Y-m-d'));
        $dateDiff = date_diff($date1,$date2);
        return (int)$dateDiff->format('%a') + 1;
    }

    /**
     * @Serializer\SerializedName("day_remaining")
     * @Serializer\Expose()
     *@Serializer\VirtualProperty()
     */
    public function getRemaingDays()
    {

        if($this->getStartDate() < new \DateTime()) {

            $now = new \DateTime();
            $date1 = date_create($this->end_date->format('Y-m-d'));
            $date2 = date_create($now->format('Y-m-d'));
            $dateDiff = date_diff($date1, $date2);
            $remaingDays = $dateDiff->format('%a') + 1;
            return (string)$remaingDays;
        }else {

            return 'N/A';

        }
    }

    /**
     * @Serializer\SerializedName("is_started")
     * @Serializer\Expose()
     *@Serializer\VirtualProperty()
     */
    public function getIsStarted()
    {

        if($this->getStartDate() < new \DateTime()) {

            return true;
        }else {

            return false;
        }
    }

    /**
     * @Serializer\SerializedName("company_id")
     * @Serializer\Expose()
     *@Serializer\VirtualProperty()
     */
    public function getCompanyIdForApi()
    {
        return $this->company->getId();
    }

    /**
     * @Serializer\SerializedName("is_global")
     * @Serializer\Expose()
     *@Serializer\VirtualProperty()
     */
    public function getIsGlobal()
    {
        return false;
    }

    /**
     * @Serializer\SerializedName("color")
     * @Serializer\Expose()
     *@Serializer\VirtualProperty()
     */
    public function getColor()
    {

        if($this->getStartDate() < new \DateTime()) {

            return "#BED62F";
        }else{

            return "#434343";
        }
    }

    /**
     * @Serializer\SerializedName("duration_text")
     * @Serializer\Expose()
     *@Serializer\VirtualProperty()
     */
    public function getDurationText()
    {

        if($this->getStartDate() < new \DateTime()) {

            return $this->getStartDateApi() . ' to '. $this->getEndDateApi() ;
        }else{

            $date1 = date_create($this->end_date->format('Y-m-d'));
            $date2 = date_create($this->start_date->format('Y-m-d'));
            $dateDiff = date_diff($date1, $date2);
            $remaingDays = $dateDiff->format('%a') + 1;
            $days = $remaingDays;
            return 'Your '.$days.' day challenge starts ' . $this->start_date->format('F jS Y');
        }
    }


}