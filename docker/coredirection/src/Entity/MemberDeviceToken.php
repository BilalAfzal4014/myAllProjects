<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * MemberDeviceToken
 *
 * @ORM\Table(name="member_device_token")
 * @ORM\Entity(repositoryClass="App\Repository\MemberDeviceTokenRepository")
 */
class MemberDeviceToken extends BaseEntity
{
	const iphone = 'iphone';
	const android = 'android';
    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="memberDeviceToken")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="device_type", type="string", length=10)
     */
    private $deviceType;

    /**
     * @var string
     *
     * @ORM\Column(name="device_token", type="text", nullable=true)
     */
    private $deviceToken;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_login", type="boolean")
     */
    private $isLogin;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="ApiKey", inversedBy="tokenKey")
     * @ORM\JoinColumn(name="api_key_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $apiKeyid;

    /**
     * @ORM\Column(name="build", type="string", length=100, nullable=true)
     */
    private $build;

    /**
     * @ORM\Column(name="version", type="string", length=100, nullable=true)
     */
    private $version;

    /**
     * @ORM\Column(name="identifier_key",type="string",nullable=true)
     */
    private $identifierKey;

    /**
     * Set deviceType
     *
     * @param string $deviceType
     *
     * @return MemberDeviceToken
     */
    public function setDeviceType($deviceType)
    {
        $this->deviceType = $deviceType;

        return $this;
    }

    /**
     * Get deviceType
     *
     * @return string
     */
    public function getDeviceType()
    {
        return $this->deviceType;
    }

    /**
     * Set deviceToken
     *
     * @param string $deviceToken
     *
     * @return MemberDeviceToken
     */
    public function setDeviceToken($deviceToken)
    {
        $this->deviceToken = $deviceToken;

        return $this;
    }

    /**
     * Get deviceToken
     *
     * @return string
     */
    public function getDeviceToken()
    {
        return $this->deviceToken;
    }

    /**
     * Set isLogin
     *
     * @param boolean $isLogin
     *
     * @return MemberDeviceToken
     */
    public function setIsLogin($isLogin)
    {
        $this->isLogin = $isLogin;

        return $this;
    }

    /**
     * Get isLogin
     *
     * @return bool
     */
    public function getIsLogin()
    {
        return $this->isLogin;
    }

    /**
     * Set authToken
     *
     * @param string $authToken
     *
     * @return MemberDeviceToken
     */
    public function setAuthToken($authToken)
    {
        $this->authToken = $authToken;

        return $this;
    }

    /**
     * Get authToken
     *
     * @return string
     */
    public function getAuthToken()
    {
        return $this->authToken;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return MemberDeviceToken
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

    /**
     * Set apiKeyid
     *
     * @param ApiKey $apiKeyid
     *
     * @return MemberDeviceToken
     */
    public function setApiKeyid(ApiKey $apiKeyid = null)
    {
        $this->apiKeyid = $apiKeyid;

        return $this;
    }

    /**
     * Get apiKeyid
     *
     * @return ApiKey
     */
    public function getApiKeyid()
    {
        return $this->apiKeyid;
    }

    public function getImageName(){
        return $this->user->getCompanyLogo();
    }

    /**
     * Set build
     *
     * @param string $build
     *
     * @return MemberDeviceToken
     */
    public function setBuild($build)
    {
        $this->build = $build;

        return $this;
    }

    /**
     * Get build
     *
     * @return string
     */
    public function getBuild()
    {
        return $this->build;
    }

    /**
     * Set version
     *
     * @param string $version
     *
     * @return MemberDeviceToken
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return mixed
     */
    public function getIdentifierKey()
    {
        return $this->identifierKey;
    }

    /**
     * @param mixed $identifierKey
     */
    public function setIdentifierKey($identifierKey)
    {
        $this->identifierKey = $identifierKey;
    }



}
