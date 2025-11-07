<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MemberKey
 *
 * @ORM\Table(name="member_key")
 * @ORM\Entity(repositoryClass="App\Repository\MemberKeyRepository")
 */
class MemberKey
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="corporateMember")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
    private $member;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="CorporateKey", inversedBy="key")
     * @ORM\JoinColumn(name="key_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $corporateKey;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * MemberKey constructor.
     * @param $created
     */
    public function __construct()
    {
        $this->created = new \DateTime('now');
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set member
     *
     * @param string $member
     *
     * @return MemberKey
     */
    public function setMember($member)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return string
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set corporateKey
     *
     * @param string $corporateKey
     *
     * @return MemberKey
     */
    public function setCorporateKey($corporateKey)
    {
        $this->corporateKey = $corporateKey;

        return $this;
    }

    /**
     * Get corporateKey
     *
     * @return string
     */
    public function getCorporateKey()
    {
        return $this->corporateKey;
    }

    public function getImageName(){
        return $this->member->getCompanyLogo();
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }




}
