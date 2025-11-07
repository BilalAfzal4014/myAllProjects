<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MemberFilter
 *
 * @ORM\Table(name="member_filter")
 * @ORM\Entity(repositoryClass="App\Repository\MemberFilterRepository")
 */
class MemberFilter extends BaseEntity
{


    /**
     * @var int
     *
     * @ORM\Column(name="Member", type="integer")
     */
    private $member;

    /**
     * @var string
     *
     * @ORM\Column(name="screen", type="string", length=50)
     */
    private $screen;

    /**
     * @var array
     *
     * @ORM\Column(name="filter", type="json_array")
     */
    private $filter;


    /**
     * Set member
     *
     * @param integer $member
     *
     * @return MemberFilter
     */
    public function setMember($member)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return int
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set screen
     *
     * @param string $screen
     *
     * @return MemberFilter
     */
    public function setScreen($screen)
    {
        $this->screen = $screen;

        return $this;
    }

    /**
     * Get screen
     *
     * @return string
     */
    public function getScreen()
    {
        return $this->screen;
    }

    /**
     * Set filter
     *
     * @param array $filter
     *
     * @return MemberFilter
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * Get filter
     *
     * @return array
     */
    public function getFilter()
    {
        return $this->filter;
    }
}

