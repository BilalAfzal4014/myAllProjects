<?php

namespace App\Messaging\Adapters;
use App\Helpers\CommonHelper;
use App\Messaging\Contracts\SendMessageContract;
use GuzzleHttp\Client as HttpClient;
use Nfilin\Libs\ApnsHttp2 as Apns;
use Nfilin\Libs\ApnsHttp2\DeviceList;

class Push implements SendMessageContract
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
            // open connection
            if (!defined('CURL_HTTP_VERSION_2_0')) {
                define('CURL_HTTP_VERSION_2_0', 3);
            }

            $body = $this->data['message'];

            $certificate = new Apns\Certificate($cert_path);
            if (isset($this->data['passphrase'])) {
                $certificate = new Apns\Certificate($cert_path, $this->data['passphrase']);
            }

            $connection = new Apns\Connection($certificate);
            if ((bool)$this->data['sandbox'] === true) {
                $connection = new Apns\Connection($certificate, ['sandbox' => true]);
            }

            $payload = new Apns\Payload();
            $payload->body = $body;
            $payload->sound = 'default';

            $apsColumns = ['title', 'backgroundColor', 'icon', 'campaign_id', 'campaign_code', 'user_id', 'track_key', 'is_silent', 'is_hero_platform', 'params'];
            foreach ($apsColumns as $column) {
                if (isset($this->data[$column])) {
                    if ($column === 'title') {
                        $payload->title = $this->data[$column];
                    } else {
                        $payload->custom_data[$column] = $this->data[$column];
                    }
                }
            }

            $receivers = new DeviceList([$this->data['device_token']]);

            $message = new Apns\Message($receivers, $payload);
            $message->topic = $this->data['app_id'];
            $message->time_to_live = 3600;

            $response = $connection->send($message);
            //$isValid = $response->valid();
            $response_array = $response->getArrayCopy();
            $response_data = $response_array[0]["response"];
            if ($response_data->status == 400) {
                throw new \Exception($response_data->reason);
            }
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