<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AppSettingLayout
 *
 * @ORM\Table(name="app_setting_layout")
 * @ORM\Entity(repositoryClass="App\Repository\AppSettingLayoutRepository")
 */
class AppSettingLayout extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="AppSetting", inversedBy="appSettingLayout")
     * @ORM\JoinColumn(name="app_setting_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $appSetting;

    /**
     * @ORM\ManyToOne(targetEntity="Layout", inversedBy="appSettingLayout")
     * @ORM\JoinColumn(name="layout_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $layout;


    /**
     * @var int
     *
     * @ORM\Column(name="sequence", type="integer", nullable=true)
     */
    private $sequence;




    /**
     * Set sequence
     *
     * @param integer $sequence
     *
     * @return AppSettingLayout
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * Get sequence
     *
     * @return int
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * Set appSetting
     *
     * @param AppSetting $appSetting
     *
     * @return AppSettingLayout
     */
    public function setAppSetting(AppSetting $appSetting)
    {
        $this->appSetting = $appSetting;

        return $this;
    }

    /**
     * Get appSetting
     *
     * @return AppSetting
     */
    public function getAppSetting()
    {
        return $this->appSetting;
    }

    /**
     * Set layout
     *
     * @param Layout $layout
     *
     * @return AppSettingLayout
     */
    public function setLayout(Layout $layout)
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * Get layout
     *
     * @return Layout
     */
    public function getLayout()
    {
        return $this->layout;
    }
}
