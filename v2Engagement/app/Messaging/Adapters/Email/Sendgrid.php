<?php

namespace App\Messaging\Adapters\Email;

use App\Messaging\Contracts\ConfigureMessageContract;
use App\Messaging\Contracts\SendMessageContract;

class Sendgrid implements ConfigureMessageContract, SendMessageContract
{
    protected $client;

    /**
     * Sendgrid API Key.
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
     * Set Sendgrid API Key.
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
    }

    /**
     * Send notification.
     *
     * @return string|array
     */
    public function send()
    {
        try {
            $email = new \SendGrid\Mail\Mail();
            $email->setFrom($this->data['from']['address'], $this->data['from']['name']);
            $email->setSubject($this->data['subject']);

            is_array($this->data['email']) ?
                $email->addTos($this->data['email']) :
                $email->addTo($this->data['email']);

            if (!empty($this->data['cc'])) {
                is_array($this->data['cc']) ?
                    $email->addCcs($this->data['cc']) :
                    $email->addCc($this->data['cc']);
            }

            if (!empty($this->data['bcc'])) {
                is_array($this->data['bcc']) ?
                    $email->addBccs($this->data['bcc']) :
                    $email->addBcc($this->data['bcc']);
            }

            $email->addContent(
                "text/html",
                $this->data['message']
            );

            $sendgrid = new \SendGrid($this->apiKey);

            $response = $sendgrid->send($email);

            return [
                'type' => 'success',
                'message' => $response->body()
            ];
        } catch (\Exception $exception) {
            return [
                'type' => 'error',
                'message' => $exception->getMessage(),
            ];
        }
    }
}