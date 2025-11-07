<?php
/**
 * Created by PhpStorm.
 * User: omair
 * Date: 29/11/18
 * Time: 11:47 AM
 */

namespace App\Gamification\Bundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Gamification\Bundle\Repository\MemberActionPointsRepository")
 * @ORM\Table(name="member_action_point")
 * @ORM\HasLifecycleCallbacks()
 */
class MemberActionPoints extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User")
     */
    private $member;


    /**
     * @ORM\ManyToOne(targetEntity="App\Gamification\Bundle\Entity\Actions",inversedBy="memberActionPoints")
     */
    private $action;

    /**
     * @ORM\OneToMany(targetEntity="App\Gamification\Bundle\Entity\ChallengeAction",mappedBy="member_action_points")
     */
    private $challengeAction;

    /**
     * @ORM\Column(name="action_date",type="date")
     */
    private $actionDate;
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
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    public function __toString()
    {
        return (string)$this->getAction();
        // TODO: Implement __toString() method.
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


}