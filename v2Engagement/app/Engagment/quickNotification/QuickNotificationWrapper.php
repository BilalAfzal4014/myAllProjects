<?php

namespace App\Engagment\quickNotification;

use App\Engagment\quickNotification\QuickNotificationHandler;

class QuickNotificationWrapper
{
    protected $notificationHandler;

    public function __construct(QuickNotificationHandler $notificationHandlerObj)
    {
        $this->notificationHandler = $notificationHandlerObj;
    }

    public function getPreData($companyId)
    {
        $platForms = $version = $build = $user = [];

        $appName = $this->notificationHandler->getPreDataAppName($companyId);
        if (sizeof($appName) > 0)
            $platForms = $this->notificationHandler->getPreDataPlatForm($companyId, $appName[0]);
        if (sizeof($platForms) > 0)
            $version = $this->notificationHandler->getPreDataVersion($companyId, $appName[0], $platForms[0]);
        if (sizeof($version) > 0)
            $build = $this->notificationHandler->getPreDataBuild($companyId, $appName[0], $platForms[0], $version[0]);
        if (sizeof($build) > 0)
            $user = $this->notificationHandler->getPreDataUser($companyId, $appName[0], $platForms[0], $version[0], $build[0]);

        return array('appName' => $appName, 'platForms' => $platForms, 'version' => $version, 'build' => $build, 'user' => $user);
    }

    public function getDataOnSelection($companyId, $step, $appName, $platForm, $version, $build)
    {
        $user = [];

        switch ($step) {
            case 0:
                $user = $this->notificationHandler->getPreDataUser($companyId, $appName, $platForm, $version, $build);
                return array('user' => $user);
                break;
            case 1:
                $platForm = $this->notificationHandler->getPreDataPlatForm($companyId, $appName);
                if ($platForm != '' && sizeof($platForm) > 0)
                    $version = $this->notificationHandler->getPreDataVersion($companyId, $appName, $platForm[0]);
                if ($version != '' && sizeof($version) > 0)
                    $build = $this->notificationHandler->getPreDataBuild($companyId, $appName, $platForm[0], $version[0]);
                if ($build != '' && sizeof($build) > 0)
                    $user = $this->notificationHandler->getPreDataUser($companyId, $appName, $platForm[0], $version[0], $build[0]);
                return array('platForms' => $platForm, 'version' => $version, 'build' => $build, 'user' => $user);
                break;
            case 2:
                $version = $this->notificationHandler->getPreDataVersion($companyId, $appName, $platForm);
                if ($version != '' && sizeof($version) > 0)
                    $build = $this->notificationHandler->getPreDataBuild($companyId, $appName, $platForm, $version[0]);
                if ($build != '' && sizeof($build) > 0)
                    $user = $this->notificationHandler->getPreDataUser($companyId, $appName, $platForm, $version[0], $build[0]);
                return array('version' => $version, 'build' => $build, 'user' => $user);
                break;
            case 3:
                $build = $this->notificationHandler->getPreDataBuild($companyId, $appName, $platForm, $version);
                if ($build != '' && sizeof($build) > 0)
                    $user = $this->notificationHandler->getPreDataUser($companyId, $appName, $platForm, $version, $build[0]);
                return array('build' => $build, 'user' => $user);
                break;
            case 4:
                $user = $this->notificationHandler->getPreDataUser($companyId, $appName, $platForm, $version, $build);
                return array('user' => $user);
                break;
        }
    }
}

/*
 *
 * SELECT DISTINCT VALUE
FROM attribute_data JOIN(
SELECT attribute_data.row_id
FROM attribute_data JOIN(
SELECT attribute_data.row_id
FROM attribute_data JOIN (
SELECT attribute_data.row_id
FROM attribute_data JOIN (
SELECT row_id
FROM attribute_data
WHERE company_id = 20 AND VALUE = "engagementDev" ) AS xq ON attribute_data.row_id = xq.row_id
WHERE VALUE = "iphone" ) AS x1 ON attribute_data.row_id = x1.row_id
WHERE VALUE = "1.0") AS x2 ON attribute_data.row_id = x2.row_id
WHERE VALUE = "1") AS x3 ON attribute_data.row_id = x3.row_id
WHERE CODE = "email"
 */