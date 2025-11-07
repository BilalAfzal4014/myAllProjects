<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ApiKey
 *
 * @ORM\Table(name="api_key")
 * @ORM\Entity(repositoryClass="App\Repository\ApiKeyRepository")
 */
class ApiKey extends BaseEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="api_key", type="string", length=50)
     */
    private $apiKey;

    /**
     * @var string
     *
     * @ORM\Column(name="api_password", type="string", length=50)
     */
    private $apiPassword;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean", options={"default":1})
     */
    private $isActive = true;

    /**
     * @ORM\OneToMany(targetEntity="MemberDeviceToken", mappedBy="apiKeyid")
     */
    protected $tokenKey;

    public function __toString()
    {
        if($this->apiKey){
            return $this->apiKey;
        }else{
            return 'API Key';
        }
    }


    /**
     * Set apiKey
     *
     * @param string $apiKey
     *
     * @return ApiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get apiKey
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set apiPassword
     *
     * @param string $apiPassword
     *
     * @return ApiKey
     */
    public function setApiPassword($apiPassword)
    {
        $this->apiPassword = $apiPassword;

        return $this;
    }

    /**
     * Get apiPassword
     *
     * @return string
     */
    public function getApiPassword()
    {
        return $this->apiPassword;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return ApiKey
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tokenKey = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add tokenKey
     *
     * @param MemberDeviceToken $tokenKey
     *
     * @return ApiKey
     */
    public function addTokenKey(MemberDeviceToken $tokenKey)
    {
        $this->tokenKey[] = $tokenKey;

        return $this;
    }

    /**
     * Remove tokenKey
     *
     * @param MemberDeviceToken $tokenKey
     */
    public function removeTokenKey(MemberDeviceToken $tokenKey)
    {
        $this->tokenKey->removeElement($tokenKey);
    }

    /**
     * Get tokenKey
     *
     * @return Collection
     */
    public function getTokenKey()
    {
        return $this->tokenKey;
    }
}
