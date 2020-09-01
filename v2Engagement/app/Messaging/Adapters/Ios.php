<?php

namespace App\Messaging\Adapters;
use App\Helpers\CommonHelper;
use App\Messaging\Contracts\SendMessageContract;

class Ios implements SendMessageContract
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
     * @return array|string
     */
    public function send()
    {
        try {
            
            $context = stream_context_create();

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

            $path = ((bool)$this->data['sandbox'] === true) ? 'ssl://gateway.sandbox.push.apple.com:2195' :
                'ssl://gateway.push.apple.com:2195';

            stream_context_set_option($context, 'ssl', 'local_cert', $cert_path);
            stream_context_set_option($context, 'ssl', 'passphrase', $this->data['passphrase']);

            $fp = stream_socket_client(
                $path,
                $error,
                $errorString,
                60,
                STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT,
                $context
            );

            if (!$fp) {
                throw new \Exception("Failed to connect: $error $errorString" . PHP_EOL);
            }

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

            $msg = chr(0) . pack('n', 32) . pack('H*', $this->data['device_token']) . pack('n', strlen($payload)) . $payload;

            $result = fwrite($fp, $msg, strlen($msg));
            stream_set_blocking ($fp, 0);
            $response = $this->checkErrorResponse($fp);
            return $response;
        } catch (\Exception $exception) {
            return [
                'type'      => 'error',
                'message'   => $exception->getMessage(),
            ];
        }
    }

    protected function checkErrorResponse($fp)
    {
        return $response = [
                'type' => 'success',
                'message' => 'Push notification sent',
                'status' => 'sent',
            ];
        
        $error_response='';
        while (!feof($fp)) {
            $error_response .= fread($fp, 8192);
        }

        $response = [];

        if (!empty($error_response)) {
            $error_response = unpack('Ccommand/Cstatus_code/Nidentifier', $error_response);

            switch ($error_response['status_code']) {
                case '0':
                    $message = 'Push notification sent';
                    $type = 'success';
                    break;
                case '1':
                    $message = 'Unable to send push notification';
                    $type = 'error';
                    break;
                case '2':
                    $message = 'Device token is missing';
                    $type = 'error';
                    break;
                case '3':
                case '6':
                    $message = 'Invalid topic/app id';
                    $type = 'error';
                    break;
                case '4':
                case '7':
                    $message = 'Invalid notification payload';
                    $type = 'error';
                    break;
                case '5':
                case '8':
                    $message = 'Invalid device token';
                    $type = 'error';
                    break;
                case '255':
                default:
                    $message = 'General processing error';
                    $type = 'error';
                    break;
            }

            $response = [
                'type' => $type,
                'message' => $message,
            ];
        } else {
            $response = [
                'type' => 'success',
                'message' => 'Push notification sent',
                'status' => 'sent',
            ];
        }

        return $response;
    }
}
