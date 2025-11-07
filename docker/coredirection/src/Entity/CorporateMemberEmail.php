<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CorporateMemberEmail
 *
 * @ORM\Table(name="corporate_member_email")
 * @ORM\Entity(repositoryClass="App\Repository\CorporateMemberEmailRepository")
 */
class CorporateMemberEmail extends  BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User",inversedBy="corporateMemberEmail")
     */
    private $member;

    private $memberListing;

    private $selectAll;
    /**
     * @ORM\Column(type="integer")
     */
    private $templateNo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User",inversedBy="")
     */
    private $corporate;
    /**
     * @ORM\Column(type="datetime")
     */
    private $actionDate;

    /**
     * @ORM\Column(name="status", type="string", columnDefinition="enum( 'pending', 'sent','failed')")
     */
    private $status;

    /**
     * @return mixed
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * @param mixed $member
     */
    public function setMember($member)
    {
        $this->member = $member;
    }

    /**
     * @return mixed
     */
    public function getTemplateNo()
    {
        return $this->templateNo;
    }

    /**
     * @param mixed $templateNo
     */
    public function setTemplateNo($templateNo)
    {
        $this->templateNo = $templateNo;
    }


    /**
     * @return mixed
     */
    public function getActionDate()
    {
        return $this->actionDate;
    }

    /**
     * @param mixed $actionDate
     */
    public function setActionDate($actionDate)
    {
        $this->actionDate = $actionDate;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getCorporate()
    {
        return $this->corporate;
    }

    /**
     * @param mixed $corporate
     */
    public function setCorporate($corporate)
    {
        $this->corporate = $corporate;
    }

    /**
     * @return mixed
     */
    public function getMemberListing()
    {
        return $this->memberListing;
    }

    /**
     * @param mixed $memberListing
     */
    public function setMemberListing($memberListing)
    {
        $this->memberListing = $memberListing;
    }

    /**
     * @return mixed
     */
    public function getSelectAll()
    {
        return $this->selectAll;
    }

    /**
     * @param mixed $selectAll
     */
    public function setSelectAll($selectAll)
    {
        $this->selectAll = $selectAll;
    }

}

