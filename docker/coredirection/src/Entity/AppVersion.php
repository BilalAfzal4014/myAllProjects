<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * AppVersion
 *
 * @ORM\Table(name="app_version")
 * @ORM\Entity(repositoryClass="App\Repository\AppVersionRepository")
 * @UniqueEntity(
 *     fields={"deviceType", "version"},
 *     errorPath="version",
 *     message="This version is already exist."
 * )
 */
class AppVersion extends  BaseEntity
{

    /**
     * @ORM\Column(name="device_type", type="string", columnDefinition="enum( 'iphone', 'android')")
     */
    private $deviceType;

    /**
     * @ORM\Column(name="version",type="string")
     *
     * @Assert\Regex("/^(\d+\.)?(\d+\.)?(\*|\d+)$/")
     *
     */
    private $version;

    /**
     * @ORM\Column(name="status",type="boolean")
     */
    private $status=false;

    /**
     * @ORM\Column(name="current_release",type="boolean")
     */
    private $currentRelease = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Application\Sonata\UserBundle\Entity\User",mappedBy="appVersion")
     */
    private $user;

    private $userCount;

    /**
     * @return mixed
     */
    public function getUserCount()
    {

        $users = $this->user->toArray();
        return count($users);
    }




    /**
     * @return mixed
     */
    public function getDeviceType()
    {
        return $this->deviceType;
    }

    /**
     * @param mixed $deviceType
     */
    public function setDeviceType($deviceType)
    {
        $this->deviceType = $deviceType;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
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

    /**
     * @return mixed
     */
    public function getCurrentRelease()
    {
        return $this->currentRelease;
    }

    /**
     * @param mixed $currentRelease
     */
    public function setCurrentRelease($currentRelease)
    {
        $this->currentRelease = $currentRelease;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    public function __toString()
    {
        return (string)'version: '.$this->version.' for Device Type:'.$this->deviceType;
        // TODO: Implement __toString() method.
    }
}


