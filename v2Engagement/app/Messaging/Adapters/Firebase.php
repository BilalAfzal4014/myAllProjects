<?php

namespace App\Messaging\Adapters;
use App\Helpers\CommonHelper;
use App\Messaging\Contracts\ConfigureMessageContract;
use App\Messaging\Contracts\SendMessageContract;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TooManyRedirectsException;

class Firebase implements ConfigureMessageContract, SendMessageContract
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
     * Set Firebase API Key.
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
        if (func_num_args() == 1) {
            $data = func_get_args()[0];

            $this->data = $data;
        }
    }

    /**
     * Send notification.
     *
     * @return string
     */
    public function send()
    {
       
        $client = new Client();
        $response = [];

        $fields = [
            'to'    => $this->arrTokens,
            'data'  => $this->data,
        ];

        $dataColumns = ['campaign_id', 'campaign_code', 'campaign_type', 'user_id', 'track_key', 'is_silent', 'is_hero_platform', 'params', 'inapp_view_link', 'notification', 'android', 'priority'];
        foreach ($dataColumns as $column) {
            if (!empty($fields['data'][$column])) {
                $fields[$column] = $fields['data'][$column];
                unset($fields['data'][$column]);
            }
        }

        if (!empty($fields['notification']['link'])) {
            $fields['notification']['link'] = trim($fields['notification']['link']);
        }

        try {
            $request = $client->post('https://fcm.googleapis.com/fcm/send', [
                'json' => $fields,
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'key=' . $this->apiKey,
                ]
            ]);

            $response = \GuzzleHttp\json_decode($request->getBody()->getContents(), true);
            if (!empty($response['failure']) && ((bool) $response['failure'] === true)) {
                $results = [];
                foreach ($response['results'] as $result) {
                    foreach ($result as $key => $item) {
                        if (!in_array($key, array_keys($results))) {
                            $results[$key] = [];
                        }

                        $results[$key] = $item;
                    }
                }

                $response['results'] = $results;
                unset($results);

                if (!empty($response['results']['error'])) {
                    $error = is_array($response['results']['error']) ?
                        implode("\n", $response['results']['error']) :
                        $response['results']['error'];

                    $response['results']['error'] = $error;
                }
            }

            return $response;
        } catch (ConnectException $exception) {
            $response['results']['error'] = $exception->getMessage();
        } catch (ClientException $exception) {
            $response['results']['error'] = $exception->getMessage();
        } catch (ServerException $exception) {
            $response['results']['error'] = $exception->getMessage();
        } catch (TooManyRedirectsException $exception) {
            $response['results']['error'] = $exception->getMessage();
        } catch (\Exception $exception) {
            $response['results']['error'] = $exception->getMessage();
        }

        return $response;
    }
}
