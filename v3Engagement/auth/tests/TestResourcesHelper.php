<?php

namespace Tests;

use App\User;

trait TestResourcesHelper
{
    protected static $user;
    protected static $itemId;
    protected static $authToken;

    /**
     * Login test company.
     */
    protected static function loginCompany()
    {
        self::$user = User::where('email', 'company@engagement.com')->firstOrFail();

        self::$authToken = self::$user->createToken('AccessToken', ['*'])->accessToken;
        self::$user->withAccessToken(self::$authToken);
    }
}