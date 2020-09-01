<?php

namespace App\Messaging\Adapters;
use App\Helpers\CommonHelper;
use App\Messaging\Contracts\SendMessageContract;
use GuzzleHttp\Client as HttpClient;


class PushHTTP2 implements SendMessageContract
{
    /**
     * Firebase API Key.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Array of users to which notification should be sent.
     *
     * @var array
     */
    protected $arrTokens;

    /**
     * @var array
     */
    protected $data;

    /**
     * Set iOS API Key.
     *
     * @param string $key
     */
    public function setApiKey($key)
    {
        $this->apiKey = $key;
    }

    /**
     * Set user list to whom notification will be sent.
     *
     * @param array $tokens
     */
    public function setMessageTokens($tokens)
    {
        $this->arrTokens = $tokens;
    }

    /**
     * Compile API request data.
     */
    public function compileData()
    {
        $this->data = $this->arrTokens;
        unset($this->arrTokens);
    }

    /**
     * Send notification.
     *
     * @throws \Exception
     *
     * @return array|string
     */
    public function send()
    {
        try {  
                                    
            $certDisk = \Storage::disk('app');
            if ($certDisk->exists($this->data['certificate']) === false) {
                $certFound = false;
                $certDisk = \Storage::disk('certificates');

                if ($certDisk->exists($this->data['certificate']) === false) {
                    $certFound = false;
                } else {
                    $certFound = true;
                }
            } else {
                $certFound = true;
            }

            if ($certFound === false) {
                throw new \Exception("IOS push certificate not found!", 404);
            }

            $cert_path = $certDisk->getDriver()->getAdapter()->getPathPrefix() . $this->data['certificate'];
            
			
			// ============= HTTP2 Curl Request ================= //
			$message = $this->data['message'];

            $dataArr = [
                'aps' => [
                    'alert' => [
                        'body'          => $message,
                        'message_type'  => 'push',
                    ],
                    'sound' => 'default'
                ],
            ];

            if (!empty($this->data['target'])) {
                $dataArr['aps']['link'] = trim($this->data['target']);
            }

            if (!empty($this->data['custom_params'])) {
                $dataArr['aps']['custom_params'] = $this->data['custom_params'];
            }

            $apsColumns = ['title', 'backgroundColor', 'icon', 'campaign_id', 'campaign_code', 'user_id', 'track_key', 'is_silent', 'is_hero_platform', 'params'];
            foreach ($apsColumns as $column) {
                if (isset($this->data[$column])) {
                    $dataArr['aps']['alert'][$column] = $this->data[$column];
                }
            }

            $payload = json_encode($dataArr);
			
			$device_token   = $this->data['device_token'];   
			$pem_file       = $cert_path;
	
			$pem_secret     = !empty($this->data['passphrase']) ? $this->data['passphrase'] :  '';
			$apns_topic     = $this->data['app_id'];
	
	
			
			$sample_alert = $payload ;
			if ((bool)$this->data['sandbox'] === true) 
				$url = "https://api.development.push.apple.com/3/device/$device_token";				
			else
				$url = "https://api.push.apple.com/3/device/$device_token";
	
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $sample_alert);
    
			//curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
			curl_setopt($ch, CURLOPT_HTTP_VERSION, 5); // 5 is numric value of HTTP2 version
	
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("apns-topic: $apns_topic"));
			curl_setopt($ch, CURLOPT_SSLCERT, $pem_file);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
			curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $pem_secret);
			//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			
			
			$response = curl_exec($ch);
			$curlInfo = curl_getinfo($ch); 	
		
		$header_size = $curlInfo["header_size"];
		$header = substr($response, 0, $header_size);
		$body = substr($response, $header_size);

		//echo "Response code is: ". $curlInfo["http_code"] ." \n\n";
		//echo "The reason is: ". $body ." \n\n";
		
		 if ($curlInfo["http_code"] != 200) {
                throw new \Exception($curlInfo["http_code"]." ".$body);
            }
     
			// ================================ //
            return [
                'type' => 'success',
                'message' => 'Push notification sent',
                'status' => 'sent',
            ];
            throw new \Exception("Unable to send push notification");
        } catch (\Exception $exception) {
            return [
                'type' => 'error',
                'message' => $exception->getMessage(),
            ];
        }
    }

}