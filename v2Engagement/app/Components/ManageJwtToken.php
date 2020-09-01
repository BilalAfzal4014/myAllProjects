<?php

namespace App\Components;

class ManageJwtToken
{

    /**
     * @var string
     */
    protected $type;


    /**
     * TargetedUsers constructor.
     *
     * @param mixed $queue
     */
    public function __construct()
    {
    }

    public static function generate($companyKey)
    {
        try {

            $userObj = \App\User::where(['company_key' => $companyKey])->first();
            //dd($userObj);
            $token = \JWTAuth::fromUser($userObj);
            $responseData = new \stdClass;
            $responseData->meta = ['code' => 200, 'status' => 'OK'];
            $responseData->data = ['token' => $token];
            return $responseData;
        } catch (\Exception $exception) {
            $responseData = new \stdClass;
            $responseData->meta = ['code' => 500, 'status' => 'error'];
            $responseData->errors = [$exception->getMessage()];
            return $responseData;
        }
    }
}
