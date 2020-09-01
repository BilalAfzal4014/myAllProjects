<?php
//if(!defined('BASEPATH')) exit('No direct script access allowed');
/*
*
 * 
  ______                     ___                      
.' ____ \                  .' ..]                     
| (___ \_| ,--.   ----     _| |_
 _.____`. `'_\ :  | |      -| |-'
| \____) |// | |, | |      | |
 \______.'\'-;__/[___]    [___]
 * 
 * 
 * 
 *  */

namespace App\Libraries;

require 'JWT/JWT.php';

class tv_jwt
{

    public function create_token($image_name, $ttl = "+1")
    {
        $key = $this->api_key;
        $token = array(
            "image" => $image_name,
            "exp" => strtotime(date("Y-m-d H:i:s", strtotime("{$ttl} Minute")))
        );


        $jwt = \JWT::encode($token, $key);

        return $jwt;


    }

    public function verify_token($id, $ttl = "+1")
    {
        $response = new \stdClass();

        $token = $id;
        try {
            $obj_token = \JWT::decode($token, $this->api_key, array('HS256'));

            if (empty($obj_token)) {
                //echo "Invalid token found.";
                $response->error = 1;
                $response->message = "Authorization failed. Please try again";
                return $response;

            }

            if (!empty($obj_token->exp)) {
                if (strtotime(date("Y-m-d H:i:s", strtotime("{$ttl} Minute"))) > strtotime(date("Y-m-d H:i:s")))
                    "";
//echo "Token Verified";           
            } else {
                //echo "Invalid token found.";
                $response->error = 1;
                $response->message = "Authorization failed. Please try again";
                return $response;
            }

            $obj_token->success = 1;

            $response->success = 1;
            $response->error = 0;
            $response->message = "Validated request.";
            $response->token = $obj_token;
            return $response;

        } catch (Exception $e) {
            debug_var($e->getMessage());

        }

    }



//    private $bot_key = '9n1qeEbW4F_1s4OV4Z73TxuD0tlZ2pWM7IevxYoRfFs';
//
//    public function create_bot_token($api_username, $api_password)
//    {
//        $key = $this->bot_key;
//        $token = array(
//            "api_username" => $api_username,
//            "api_password" => $api_password,
//            "bot_key" => $key,
//            "exp" => strtotime(date("Y-m-d H:i:s", strtotime("{$this->token_ttl} Minute")))
//        );
//
//
//        $jwt = \JWT::encode($token, $key);
//
//        return $jwt;
//
//
//    }
//
//
//    public function verify_bot_token($id)
//    {
//
//        $token = $id;
//        try {
//            $obj_token = \JWT::decode($token, $this->bot_key, array('HS256'));
//
//            if (empty($obj_token)) {
//                echo "Invalid token found.";
//                die("");
//            }
//
//            if (!empty($obj_token->exp)) {
//                if (strtotime(date("Y-m-d H:i:s", strtotime("{$this->token_ttl} Minute"))) > strtotime(date("Y-m-d H:i:s")))
//                    "";
////echo "Token Verified";
//            } else {
//                echo "Invalid token found.";
//            }
//
//            $obj_token->success = 1;
//            return $obj_token;
//
//        } catch (Exception $e) {
//            debug_var($e->getMessage());
//
//        }
//
//    }



    public function engagiveCreateToken( $activeApiKey, $data )
    {

        return \JWT::encode($data, $activeApiKey);
    }
    public function engagiveGetToken( $token, $data )
    {

        return \JWT::decode($token, $data,array('HS256'));
    }


//    public function engagiveVerifyToken( $token )
//    {
//        try {
//            $objToken = \JWT::decode($token, $this->activeApiKey, array('HS256'));
//            if (empty($objToken)) {
//                return "Invalid token found.22";
//            }
//            if (!empty($objToken->exp)) {
//                if (strtotime(date("Y-m-d H:i:s", strtotime("{$this->tokenExpiryTime} Minute"))) > strtotime(date("Y-m-d H:i:s"))) {
//                    $objToken->success = 1;
//                }
//            } else {
//                return "Invalid token found.";
//            }
//            return $objToken;
//
//        } catch (Exception $e) {
//            debug_var($e->getMessage());
//
//        }
//    }

}

?>