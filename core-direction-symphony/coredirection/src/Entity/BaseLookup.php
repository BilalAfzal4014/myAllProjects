<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * BaseLookup
 *
 * @ORM\Table(name="base_lookup")
 * @ORM\Entity(repositoryClass="App\Repository\BaseLookupRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 *     fields={"name"},
 *     errorPath="name",
 *     message="The name field must be unique!"
 * )
 */
class BaseLookup extends BaseEntity
{

    public function __construct()
    {
        $this->baselookup = new ArrayCollection(); // self relation of current entity

    }

    public function __toString()
    {
        return (string)$this->name;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=60)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=60)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=100)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="BaseLookup", mappedBy="parentId")
     */
    private $baselookup;

    /**
     * @ORM\ManyToOne(targetEntity="BaseLookup", inversedBy="baselookup")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parentId;


    /**
     * @ORM\OneToMany(targetEntity="ExerciseLookup", mappedBy="baseLookup")
     */
    private $exercise_lookup;

    /**
     * Set code
     *
     * @param string $code
     * @return BaseLookup
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return BaseLookup
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return BaseLookup
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add baselookup
     *
     * @param BaseLookup $baselookup
     * @return BaseLookup
     */
    public function addBaselookup(BaseLookup $baselookup)
    {
        $this->baselookup[] = $baselookup;

        return $this;
    }

    /**
     * Remove baselookup
     *
     * @param BaseLookup $baselookup
     */
    public function removeBaselookup(BaseLookup $baselookup)
    {
        $this->baselookup->removeElement($baselookup);
    }

    /**
     * Get baselookup
     *
     * @return Collection
     */
    public function getBaselookup()
    {
        return $this->baselookup;
    }

    /**
     * Set parentId
     *
     * @param BaseLookup $parentId
     * @return BaseLookup
     */
    public function setParentId(BaseLookup $parentId = null)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     *
     * @return BaseLookup
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @return mixed
     */
    public function getExerciseLookup()
    {
        return $this->exercise_lookup;
    }
}
