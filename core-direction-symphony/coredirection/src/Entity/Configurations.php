<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Configurations
 *
 * @ORM\Table(name="configurations")
 * @ORM\Entity(repositoryClass="App\Repository\ConfigurationsRepository")
 * @UniqueEntity(
 *     fields={"keyName"},
 *     errorPath="keyName",
 *     message="This key is already exist."
 * )
 */
class Configurations extends  BaseEntity
{
    /**
     * @ORM\Column(type="string",name="key_name")
     */
    private $keyName;
    /**
     * @ORM\Column(type="text",name="value_data")
     */
    private $valueData;
    /**
     * @ORM\Column(type="boolean",name="is_active")
     */
    private $isActive;

    /**
     * @return mixed
     */
    public function getKeyName()
    {
        return $this->keyName;
    }

    /**
     * @param mixed $keyName
     */
    public function setKeyName($keyName)
    {
        $this->keyName = $keyName;
    }

    /**
     * @return mixed
     */
    public function getValueData()
    {
        return $this->valueData;
    }

    /**
     * @param mixed $valueData
     */
    public function setValueData($valueData)
    {
        $this->valueData = $valueData;
    }

    /**
     * @return mixed
     */
    public function getisActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    public function __toString()
    {
        return (string)$this->getKeyName();
        // TODO: Implement __toString() method.
    }
}

