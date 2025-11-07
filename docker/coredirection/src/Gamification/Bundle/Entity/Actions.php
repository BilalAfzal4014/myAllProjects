<?php

namespace App\Gamification\Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use JMS\Serializer\Annotation as Serializer;
/**
 * Actions
 *
 * @ORM\Table(name="gmf_actions")
 * @Serializer\ExclusionPolicy("all")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Actions extends BaseEntity
{

    public function __toString()
    {
        return (string)$this->name;
    }

    /**
     * @var string
     *@Serializer\Expose()
     * @ORM\Column(name="code", type="string", length=25)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Gamification\Bundle\Entity\MemberActionPoints",mappedBy="action")
     */
    private $memberActionPoints;
    /**
     * @var string
     *@Serializer\Expose()
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;


    /**
     * @var int
     *@Serializer\Expose()
     * @ORM\Column(name="point", type="integer", nullable=true)
     */
    private $value;

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param int $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getMemberActionPoints()
    {
        return $this->memberActionPoints;
    }

    /**
     * @param mixed $memberActionPoints
     */
    public function setMemberActionPoints($memberActionPoints)
    {
        $this->memberActionPoints = $memberActionPoints;
    }


}
