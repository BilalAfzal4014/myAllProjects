<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * DeviceConfig
 *
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="device_config")
 * @ORM\Entity(repositoryClass="App\Repository\DeviceConfigRepository")
 */
class DeviceConfig
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
     *
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="deviceconfig")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="cascade")
     */

    private $user;



    /**
     * @var array
     *
     * @ORM\Column(name="config", type="text")
     */
    private $config;

    /**
     * @var string
     *
     * @ORM\Column(name="lang", type="string", columnDefinition=" enum('en','ar','de') ")
     */
    private $lang;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime")
     */
    private $createdDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_date", type="datetime")
     */
    private $updatedDate;


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
     * Set config
     *
     * @param array $config
     *
     * @return DeviceConfig
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Get config
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set lang
     *
     * @param string $lang
     *
     * @return DeviceConfig
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Get lang
     *
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @ORM\PrePersist
     */
    public function onCreate()
    {
        $this->createdDate = new \DateTime();
        $this->updatedDate = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function onUpdate()
    {
        $this->updatedDate = new \DateTime();
    }


    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return DeviceConfig
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set updatedDate
     *
     * @param \DateTime $updatedDate
     *
     * @return DeviceConfig
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    /**
     * Get updatedDate
     *
     * @return \DateTime
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return DeviceConfig
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
}
