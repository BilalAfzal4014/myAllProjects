<?php
/**
 * Created by PhpStorm.
 * User: omair
 * Date: 29/11/18
 * Time: 11:45 AM
 */

namespace App\Gamification\Bundle\Entity;


use Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use phpDocumentor\Reflection\Types\This;

/**
 *  @ORM\Entity(repositoryClass="App\Gamification\Bundle\Repository\MemberChallengeRepository")
 * @Serializer\ExclusionPolicy("all")
 * @ORM\Table(name="member_challenge_invite")
 * @ORM\HasLifecycleCallbacks()
 */
class MemberChallenge extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User",inversedBy="challanges")
     */
    private $member;

    /**
     * @ORM\ManyToOne(targetEntity="App\Gamification\Bundle\Entity\Challenge")
     * @Serializer\Expose()
     */
    private $challenge;

    /**
     * @ORM\Column(type="string", columnDefinition="enum( 'Pending', 'Confirmed','Reject')",nullable=true)
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

    public function getCheckInCount()
    {
        global $kernel;
        $em = $kernel->getContainer()->get('doctrine')->getManager();
        $count = $em->getRepository('GamificationBundle:MemberActionPoints')->getUserChallengeDetailExport($this->challenge,$this->member,'checkIn');
        return count($count);
    }

    public function getArticleCount()
    {
        global $kernel;
        $em = $kernel->getContainer()->get('doctrine')->getManager();
        $count = $em->getRepository('GamificationBundle:MemberActionPoints')->getUserChallengeDetailExport($this->challenge,$this->member,'ARTICLE');
        return count($count);
    }

    public function getWorkoutCount()
    {
        global $kernel;
        $em = $kernel->getContainer()->get('doctrine')->getManager();
        $count = $em->getRepository('GamificationBundle:MemberActionPoints')->getUserChallengeDetailExport($this->challenge,$this->member,'WORKOUT');
        return count($count);
    }

    public function getTotalPoint()
    {
        global $kernel;
        $em = $kernel->getContainer()->get('doctrine')->getManager();
        $userObject = $em->getRepository('ApplicationSonataUserBundle:User')->getTotalPointsExport($this->challenge,$this->member);
//        dump($leaderBoad,$userObject);
        return $userObject;
    }
    public function __toString()
    {
        return (string)$this->getChallenge();
        // TODO: Implement __toString() method.
    }
}