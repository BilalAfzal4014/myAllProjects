<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MemberInvitation
 *
 * @ORM\Table(name="member_invitation")
 * @ORM\Entity(repositoryClass="App\Repository\MemberInvitationRepository")
 */
class MemberInvitation extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CorporateKey",inversedBy="memberInvitation")
     */
    private $corporate_key;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\InvitationTemplate",inversedBy="memberInvitation",)
     */
    private $invitation_template;

    /**
     * @ORM\Column(type="string",length=255,name="email")
     */
    private $email;


    /**
     * @ORM\Column(type="datetime",name="sent_at",nullable=true)
     */
    private $sentAt;

    /**
     *
     * @ORM\Column(name="invitation_status", type="string", columnDefinition="enum('enqueue','pending','confirmed')")
     */
    private $invitationStatus;

    /**
     * @return mixed
     */
    public function getCorporateKey()
    {
        return $this->corporate_key;
    }

    /**
     * @param mixed $corporate_key
     */
    public function setCorporateKey($corporate_key)
    {
        $this->corporate_key = $corporate_key;
    }

    /**
     * @return mixed
     */
    public function getInvitationTemplate()
    {
        return $this->invitation_template;
    }

    /**
     * @param mixed $invitation_template
     */
    public function setInvitationTemplate($invitation_template)
    {
        $this->invitation_template = $invitation_template;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getSentAt()
    {
        return $this->sentAt;
    }

    /**
     * @param mixed $sentAt
     */
    public function setSentAt($sentAt)
    {
        $this->sentAt = $sentAt;
    }

    /**
     * @return mixed
     */
    public function getInvitationStatus()
    {
        return $this->invitationStatus;
    }

    /**
     * @param mixed $invitationStatus
     */
    public function setInvitationStatus($invitationStatus)
    {
        $this->invitationStatus = $invitationStatus;
    }

    /**
     * @return object|null
     */
    public function getUser()
    {
        global $kernel;
        $em = $kernel->getContainer()->get('doctrine')->getManager();
        $user = $em->getRepository('ApplicationSonataUserBundle:User')
            ->findOneBy(array('email' => $this->email));
        return $user;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        $user = $this->getUser();
        if (!empty($user)) {
            return $user->getUserName();
        } else {
            return '';
        }
    }
}

