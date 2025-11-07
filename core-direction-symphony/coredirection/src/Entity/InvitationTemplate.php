<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InvitationTemplate
 *
 * @ORM\Table(name="invitation_template")
 * @ORM\Entity(repositoryClass="App\Repository\InvitationTemplateRepository")
 */
class InvitationTemplate extends BaseEntity
{
    /**
     * @ORM\Column(type="string",length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $template;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MemberInvitation", mappedBy="invitation_template")
     */
    private $memberInvitation;

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
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return mixed
     */
    public function getMemberInvitation()
    {
        return $this->memberInvitation;
    }

    /**
     * @param mixed $memberInvitation
     */
    public function setMemberInvitation($memberInvitation)
    {
        $this->memberInvitation = $memberInvitation;
    }


}

