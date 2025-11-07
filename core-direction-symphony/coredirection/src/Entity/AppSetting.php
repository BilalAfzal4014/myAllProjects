<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * AppSetting
 *
 * @ORM\Table(name="app_setting")
 * @ORM\Entity(repositoryClass="App\Repository\AppSettingRepository")
 * @Vich\Uploadable
 */
class AppSetting extends BaseEntity
{


    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=50, unique=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     *
     * @Vich\UploadableField(mapping="setting_logo", fileNameProperty="logo")
     *
     * @var File
     */
    private $logoFile;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     *
     * @Vich\UploadableField(mapping="setting_loader", fileNameProperty="loaderImage")
     *
     * @var File
     */
    private $loaderFile;

    /**
     * @var string
     *
     * @ORM\Column(name="loader_image", type="string", length=255, nullable=true)
     */
    private $loaderImage;

    /**
     * @var string
     *
     * @ORM\Column(name="build", type="string", length=255, nullable=true)
     */
    private $build;

    /**
     * @var string
     *
     * @ORM\Column(name="version", type="string", length=255, nullable=true)
     */
    private $version;

    /**
     * @var string
     *
     * @ORM\Column(name="lang_list", type="simple_array", length=255, nullable=true)
     */
    private $langList;

    /**
     * @var string
     *
     * @ORM\Column(name="base_url", type="string", length=255, nullable=true)
     */
    private $baseUrl;


    /**
     *
     * @ORM\Column(name="name_ar", type="text", nullable=true)
     */
    private $nameAr;


    /**
     * @var string
     *
     * @ORM\Column(name="description_ar", type="text", nullable=true)
     */
    private $descriptionAr;

    /**
     * @ORM\OneToMany(targetEntity="AppSettingLayout", mappedBy="appSetting")
     */
    private $appSettingLayout;

    /**
     * @ORM\Column(name="authentication", type="string", nullable=true, length=100)
     */
    private $authentication;

    /**
     * @ORM\Column(name="gamify_enabled", type="boolean", options={"default":0})
     */
    private $gamifyEnabled = false;

    /**
     * @ORM\Column(name="published", type="boolean", options={"default":0})
     */
    private $published = false;

    /**
     * @ORM\Column(name="gamify_base_url", type="string", length=100, nullable=true)
     */
    private $gamifyBaseUrl;

    /**
     * @ORM\Column(name="gamify_app_key", type="string", length=255, nullable=true)
     */
    private $gamifyAppKey;

    /**
     * @ORM\Column(name="url", type="string", length=100, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(name="enable", type="boolean", options={"default":0})
     */
    private $enable = false;

    /**
     * @ORM\Column(name="error_log_enabled", type="boolean", options={"default":0})
     */
    private $errorLogEnabled;

    /**
     * @ORM\Column(name="font", type="string", length=100, nullable=true)
     */
    private $font;

    /**
     * @ORM\Column(name="title", type="string", length=100, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(name="txt_unhl", type="string", length=100, nullable=true)
     */
    private $txtUnhl;

    /**
     * @ORM\Column(name="txt_hl", type="string", length=100, nullable=true)
     */
    private $txthl;

    /**
     * @ORM\Column(name="bg_unhl", type="string", length=100, nullable=true)
     */

    private $bgUnhl;

    /**
     * @ORM\Column(name="bg_hl", type="string", length=100, nullable=true)
     */

    private $bghl;

    /**
     * @ORM\Column(name="sec_title_txt_unhl", type="string", length=100, nullable=true)
     */

    private $secTitleTxtUnhl;

    /**
     * @ORM\Column(name="sec_title_txt_hl", type="string", length=100, nullable=true)
     */

    private $secTitleTxtHl;

    /**
     * @ORM\Column(name="sec_bg_unhl", type="string", length=100, nullable=true)
     */

    private $secBgUnhl;

    /**
     * @ORM\Column(name="sec_bg_hl", type="string", length=100, nullable=true)
     */
    private $secBgHl;


    /**
     * @ORM\Column(name="device_type", type="string", columnDefinition="enum('android','ios')")
     */
    private $deviceType;

    /**
     * @ORM\Column(name="company_name", type="string", length=100)
     */
    private $companyName;

    /**
     * @ORM\Column(name="postfix", type="string", length=100, nullable=true)
     */
    private $postfix;

    /**
     * @ORM\Column(name="main_authentication", type="string", length=100, nullable=true)
     */
    private $mainAuthentication;

    /**
     *
     * @Vich\UploadableField(mapping="appsetting_appicon", fileNameProperty="appIcon")
     *
     * @var File
     */
    private $appIconFile;
    
    /**
     * @ORM\Column(name="app_icon", type="string", length=255, nullable=true)
     */
    private $appIcon;

    /**
     * @ORM\Column(name="privacy_policy", type="string", length=100, nullable=true)
     */
    private $privacyPolicy;

    /**
     * @ORM\Column(name="agreement_content", type="string", length=100, nullable=true)
     */
    private $agreementContent;

    /**
     * @ORM\Column(name="push_notification_enable", type="boolean", options={"default": 0})
     */
    private $pushNotificationEnable = false;

    /**
     * @ORM\Column(name="messaging_enable", type="boolean", options={"default": 0})
     */
    private $messagingEnable = false;

    /**
     * @ORM\Column(name="messaging_base_url", type="string", length=100, nullable=true)
     */
    private $messagingBaseUrl;

    /**
     * @ORM\Column(name="messaging_postfix", type="string", length=100, nullable=true)
     */
    private $messagingPostfix;

    /**
     * @ORM\Column(name="messaging_authentication", type="string", length=100, nullable=true)
     */
    private $messagingAuthentication;

    
    /**
     * Set code
     *
     * @param string $code
     *
     * @return AppSetting
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
     *
     * @return AppSetting
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
     *
     * @return AppSetting
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
     * Set logo
     *
     * @param string $logo
     *
     * @return AppSetting
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set loaderImage
     *
     * @param string $loaderImage
     *
     * @return AppSetting
     */
    public function setLoaderImage($loaderImage)
    {
        $this->loaderImage = $loaderImage;

        return $this;
    }

    /**
     * Get loaderImage
     *
     * @return string
     */
    public function getLoaderImage()
    {
        return $this->loaderImage;
    }

    /**
     * Set build
     *
     * @param string $build
     *
     * @return AppSetting
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
     * @return AppSetting
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
     * Set langList
     *
     * @param string $langList
     *
     * @return AppSetting
     */
    public function setLangList($langList)
    {
        $this->langList = $langList;

        return $this;
    }

    /**
     * Get langList
     *
     * @return string
     */
    public function getLangList()
    {
        return $this->langList;
    }

    /**
     * Set baseUrl
     *
     * @param string $baseUrl
     *
     * @return AppSetting
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * Get baseUrl
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Set nameAr
     *
     * @param string $nameAr
     *
     * @return AppSetting
     */
    public function setNameAr($nameAr)
    {
        $this->nameAr = $nameAr;

        return $this;
    }

    /**
     * Get nameAr
     *
     * @return string
     */
    public function getNameAr()
    {
        return $this->nameAr;
    }

    /**
     * Set descriptionAr
     *
     * @param string $descriptionAr
     *
     * @return AppSetting
     */
    public function setDescriptionAr($descriptionAr)
    {
        $this->descriptionAr = $descriptionAr;

        return $this;
    }

    /**
     * Get descriptionAr
     *
     * @return string
     */
    public function getDescriptionAr()
    {
        return $this->descriptionAr;
    }

    /**
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return AppSetting
     * @throws \Exception
     */
    public function setLogoFile(File $image = null)
    {
        $this->logoFile = $image;

        if ($image) {

            $this->setUpdatedDate(new \DateTime());
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getLogoFile()
    {
        return $this->logoFile;
    }

    /**
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return AppSetting
     * @throws \Exception
     */
    public function setLoaderFile(File $image = null)
    {
        $this->loaderFile = $image;

        if ($image) {

            $this->setUpdatedDate(new \DateTime());
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getLoaderFile()
    {
        return $this->loaderFile;
    }

    public function getImageName()
    {
        if ($this->logo) {
            return '/images/setting_logo/'.$this->logo;
        } else {
            return '/icon/default_image.png';
        }
    }

    public function getLoaderFileName()
    {
        if ($this->loaderImage) {
            return '/images/setting_loader/'.$this->loaderImage;
        } else {
            return '/icon/default_image.png';
        }
    }

    public function getAppIconFileName()
    {

        if ($this->appIcon) {
            return '/images/appsetting_appicon/'.$this->appIcon;
        } else {
            return '/icon/default_image.png';
        }
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->appSettingLayout = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add appSettingLayout
     *
     * @param AppSettingLayout $appSettingLayout
     *
     * @return AppSetting
     */
    public function addAppSettingLayout(AppSettingLayout $appSettingLayout)
    {
        $this->appSettingLayout[] = $appSettingLayout;

        return $this;
    }

    /**
     * Remove appSettingLayout
     *
     * @param AppSettingLayout $appSettingLayout
     */
    public function removeAppSettingLayout(AppSettingLayout $appSettingLayout)
    {
        $this->appSettingLayout->removeElement($appSettingLayout);
    }

    /**
     * Get appSettingLayout
     *
     * @return Collection
     */
    public function getAppSettingLayout()
    {
        return $this->appSettingLayout;
    }

    /**
     * Set authentication
     *
     * @param string $authentication
     *
     * @return AppSetting
     */
    public function setAuthentication($authentication)
    {
        $this->authentication = $authentication;

        return $this;
    }

    /**
     * Get authentication
     *
     * @return string
     */
    public function getAuthentication()
    {
        return $this->authentication;
    }


    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set enable
     *
     * @param boolean $enable
     *
     * @return AppSetting
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;

        return $this;
    }

    /**
     * Get enable
     *
     * @return boolean
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * Set errorLogEnabled
     *
     * @param boolean $errorLogEnabled
     *
     * @return AppSetting
     */
    public function setErrorLogEnabled($errorLogEnabled)
    {
        $this->errorLogEnabled = $errorLogEnabled;

        return $this;
    }

    /**
     * Get errorLogEnabled
     *
     * @return boolean
     */
    public function getErrorLogEnabled()
    {
        return $this->errorLogEnabled;
    }

    /**
     * Set font
     *
     * @param string $font
     *
     * @return AppSetting
     */
    public function setFont($font)
    {
        $this->font = $font;

        return $this;
    }

    /**
     * Get font
     *
     * @return string
     */
    public function getFont()
    {
        return $this->font;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return AppSetting
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }



    /**
     * Set gamifyEnabled
     *
     * @param boolean $gamifyEnabled
     *
     * @return AppSetting
     */
    public function setGamifyEnabled($gamifyEnabled)
    {
        $this->gamifyEnabled = $gamifyEnabled;

        return $this;
    }

    /**
     * Get gamifyEnabled
     *
     * @return boolean
     */
    public function getGamifyEnabled()
    {
        return $this->gamifyEnabled;
    }

    /**
     * Set gamifyBaseUrl
     *
     * @param string $gamifyBaseUrl
     *
     * @return AppSetting
     */
    public function setGamifyBaseUrl($gamifyBaseUrl)
    {
        $this->gamifyBaseUrl = $gamifyBaseUrl;

        return $this;
    }

    /**
     * Get gamifyBaseUrl
     *
     * @return string
     */
    public function getGamifyBaseUrl()
    {
        return $this->gamifyBaseUrl;
    }

    /**
     * Set gamifyAppKey
     *
     * @param string $gamifyAppKey
     *
     * @return AppSetting
     */
    public function setGamifyAppKey($gamifyAppKey)
    {
        $this->gamifyAppKey = $gamifyAppKey;

        return $this;
    }

    /**
     * Get gamifyAppKey
     *
     * @return string
     */
    public function getGamifyAppKey()
    {
        return $this->gamifyAppKey;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return AppSetting
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Set txtUnhl
     *
     * @param string $txtUnhl
     *
     * @return AppSetting
     */
    public function setTxtUnhl($txtUnhl)
    {
        $this->txtUnhl = $txtUnhl;

        return $this;
    }

    /**
     * Get txtUnhl
     *
     * @return string
     */
    public function getTxtUnhl()
    {
        return $this->txtUnhl;
    }

    /**
     * Set txthl
     *
     * @param string $txthl
     *
     * @return AppSetting
     */
    public function setTxthl($txthl)
    {
        $this->txthl = $txthl;

        return $this;
    }

    /**
     * Get txthl
     *
     * @return string
     */
    public function getTxthl()
    {
        return $this->txthl;
    }

    /**
     * Set bgUnhl
     *
     * @param string $bgUnhl
     *
     * @return AppSetting
     */
    public function setBgUnhl($bgUnhl)
    {
        $this->bgUnhl = $bgUnhl;

        return $this;
    }

    /**
     * Get bgUnhl
     *
     * @return string
     */
    public function getBgUnhl()
    {
        return $this->bgUnhl;
    }

    /**
     * Set bghl
     *
     * @param string $bghl
     *
     * @return AppSetting
     */
    public function setBghl($bghl)
    {
        $this->bghl = $bghl;

        return $this;
    }

    /**
     * Get bghl
     *
     * @return string
     */
    public function getBghl()
    {
        return $this->bghl;
    }

    /**
     * Set secTitleTxtUnhl
     *
     * @param string $secTitleTxtUnhl
     *
     * @return AppSetting
     */
    public function setSecTitleTxtUnhl($secTitleTxtUnhl)
    {
        $this->secTitleTxtUnhl = $secTitleTxtUnhl;

        return $this;
    }

    /**
     * Get secTitleTxtUnhl
     *
     * @return string
     */
    public function getSecTitleTxtUnhl()
    {
        return $this->secTitleTxtUnhl;
    }

    /**
     * Set secTitleTxtHl
     *
     * @param string $secTitleTxtHl
     *
     * @return AppSetting
     */
    public function setSecTitleTxtHl($secTitleTxtHl)
    {
        $this->secTitleTxtHl = $secTitleTxtHl;

        return $this;
    }

    /**
     * Get secTitleTxtHl
     *
     * @return string
     */
    public function getSecTitleTxtHl()
    {
        return $this->secTitleTxtHl;
    }

    /**
     * Set secBgUnhl
     *
     * @param string $secBgUnhl
     *
     * @return AppSetting
     */
    public function setSecBgUnhl($secBgUnhl)
    {
        $this->secBgUnhl = $secBgUnhl;

        return $this;
    }

    /**
     * Get secBgUnhl
     *
     * @return string
     */
    public function getSecBgUnhl()
    {
        return $this->secBgUnhl;
    }

    /**
     * Set secBgHl
     *
     * @param string $secBgHl
     *
     * @return AppSetting
     */
    public function setSecBgHl($secBgHl)
    {
        $this->secBgHl = $secBgHl;

        return $this;
    }

    /**
     * Get secBgHl
     *
     * @return string
     */
    public function getSecBgHl()
    {
        return $this->secBgHl;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return AppSetting
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set deviceType
     *
     * @param string $deviceType
     *
     * @return AppSetting
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
     * Set companyName
     *
     * @param string $companyName
     *
     * @return AppSetting
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set postfix
     *
     * @param string $postfix
     *
     * @return AppSetting
     */
    public function setPostfix($postfix)
    {
        $this->postfix = $postfix;

        return $this;
    }

    /**
     * Get postfix
     *
     * @return string
     */
    public function getPostfix()
    {
        return $this->postfix;
    }

    /**
     * Set mainAuthentication
     *
     * @param string $mainAuthentication
     *
     * @return AppSetting
     */
    public function setMainAuthentication($mainAuthentication)
    {
        $this->mainAuthentication = $mainAuthentication;

        return $this;
    }

    /**
     * Get mainAuthentication
     *
     * @return string
     */
    public function getMainAuthentication()
    {
        return $this->mainAuthentication;
    }

    /**
     * Set appIcon
     *
     * @param string $appIcon
     *
     * @return AppSetting
     */
    public function setAppIcon($appIcon)
    {
        $this->appIcon = $appIcon;

        return $this;
    }

    /**
     * Get appIcon
     *
     * @return string
     */
    public function getAppIcon()
    {
        return $this->appIcon;
    }

    /**
     * Set privacyPolicy
     *
     * @param string $privacyPolicy
     *
     * @return AppSetting
     */
    public function setPrivacyPolicy($privacyPolicy)
    {
        $this->privacyPolicy = $privacyPolicy;

        return $this;
    }

    /**
     * Get privacyPolicy
     *
     * @return string
     */
    public function getPrivacyPolicy()
    {
        return $this->privacyPolicy;
    }

    /**
     * Set agreementContent
     *
     * @param string $agreementContent
     *
     * @return AppSetting
     */
    public function setAgreementContent($agreementContent)
    {
        $this->agreementContent = $agreementContent;

        return $this;
    }

    /**
     * Get agreementContent
     *
     * @return string
     */
    public function getAgreementContent()
    {
        return $this->agreementContent;
    }

    /**
     * Set pushNotificationEnable
     *
     * @param boolean $pushNotificationEnable
     *
     * @return AppSetting
     */
    public function setPushNotificationEnable($pushNotificationEnable)
    {
        $this->pushNotificationEnable = $pushNotificationEnable;

        return $this;
    }

    /**
     * Get pushNotificationEnable
     *
     * @return boolean
     */
    public function getPushNotificationEnable()
    {
        return $this->pushNotificationEnable;
    }

    /**
     * Set messagingEnable
     *
     * @param boolean $messagingEnable
     *
     * @return AppSetting
     */
    public function setMessagingEnable($messagingEnable)
    {
        $this->messagingEnable = $messagingEnable;

        return $this;
    }

    /**
     * Get messagingEnable
     *
     * @return boolean
     */
    public function getMessagingEnable()
    {
        return $this->messagingEnable;
    }

    /**
     * Set messagingBaseUrl
     *
     * @param string $messagingBaseUrl
     *
     * @return AppSetting
     */
    public function setMessagingBaseUrl($messagingBaseUrl)
    {
        $this->messagingBaseUrl = $messagingBaseUrl;

        return $this;
    }

    /**
     * Get messagingBaseUrl
     *
     * @return string
     */
    public function getMessagingBaseUrl()
    {
        return $this->messagingBaseUrl;
    }

    /**
     * Set messagingPostfix
     *
     * @param string $messagingPostfix
     *
     * @return AppSetting
     */
    public function setMessagingPostfix($messagingPostfix)
    {
        $this->messagingPostfix = $messagingPostfix;

        return $this;
    }

    /**
     * Get messagingPostfix
     *
     * @return string
     */
    public function getMessagingPostfix()
    {
        return $this->messagingPostfix;
    }

    /**
     * Set messagingAuthentication
     *
     * @param string $messagingAuthentication
     *
     * @return AppSetting
     */
    public function setMessagingAuthentication($messagingAuthentication)
    {
        $this->messagingAuthentication = $messagingAuthentication;

        return $this;
    }

    /**
     * Get messagingAuthentication
     *
     * @return string
     */
    public function getMessagingAuthentication()
    {
        return $this->messagingAuthentication;
    }

    /**
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return AppSetting
     * @throws \Exception
     */
    public function setAppIconFile(File $image = null)
    {
        $this->appIconFile = $image;

        if ($image) {

            $this->setUpdatedDate(new \DateTime());
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getAppIconFile()
    {
        return $this->appIconFile;
    }
}
