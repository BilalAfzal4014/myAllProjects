<?php

namespace App\Gamification\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChallengeAction
 *
 * @ORM\Table(name="challenge_action")
 * @ORM\Entity(repositoryClass="App\Gamification\Bundle\Repository\ChallengeActionRepository")
 */
class ChallengeAction
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
     * @ORM\ManyToOne(targetEntity="App\Gamification\Bundle\Entity\Challenge",inversedBy="challengeAction")
     */
    private $challenge;

    /**
     * @ORM\ManyToOne(targetEntity="App\Gamification\Bundle\Entity\MemberActionPoints",inversedBy="challengeAction")
     */
    private $member_action_points;
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
     * @return mixed
     */
    public function getChallenge()
    {
        return $this->challenge;
    }

    /**
     * @param mixed $challenge
     */
    public function setChallenge($challenge)
    {
        $this->challenge = $challenge;
    }

    /**
     * @return mixed
     */
    public function getMemberActionPoints()
    {
        return $this->member_action_points;
    }

    /**
     * @param mixed $memberActionPoints
     */
    public function setMemberActionPoints($memberActionPoints)
    {
        $this->member_action_points = $memberActionPoints;
    }


}

