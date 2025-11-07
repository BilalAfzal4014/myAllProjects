<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Query\Expr\Base;

/**
 * MemberAction
 *
 * @ORM\Table(name="member_action")
 * @ORM\Entity(repositoryClass="App\Repository\MemberActionRepository")
 */
class MemberAction extends BaseEntity
{

    public function __toString()
    {
        //return (string)$this->getUser()->getUserName();
        return "Member Action";
    }

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="memberAction")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
    private $user;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Actions", inversedBy="memberAction")
     * @ORM\JoinColumn(name="action_id", referencedColumnName="id")
     */
    private $action;

    /**
     * @var bool
     *
     * @ORM\Column(name="isCompleted", type="boolean", nullable=true)
     */
    private $isCompleted;

    /**
     * @var bool
     *
     * @ORM\Column(name="value", type="string", nullable=true)
     */
    private $value;




    /**
     * Set action
     *
     * @param string $action
     *
     * @return MemberAction
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set isCompleted
     *
     * @param boolean $isCompleted
     *
     * @return MemberAction
     */
    public function setIsCompleted($isCompleted)
    {
        $this->isCompleted = $isCompleted;

        return $this;
    }

    /**
     * Get isCompleted
     *
     * @return bool
     */
    public function getIsCompleted()
    {
        return $this->isCompleted;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return MemberAction
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
     * Set value
     *
     * @param string $value
     *
     * @return MemberAction
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    public function getImageName()
    {

        if ($this->action) {
            return $this->action->getImageName();
        } else {
            return 'default_image.png';
        }
    }

    public function getCompanyLogo()
    {

        if ($this->user) {
            return $this->user->getcompanyLogo();
        } else {
            return 'default_image.png';
        }
    }
}
