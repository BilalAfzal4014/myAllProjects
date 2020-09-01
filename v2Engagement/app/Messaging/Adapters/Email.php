<?php

namespace App\Messaging\Adapters;

use App\Messaging\Contracts\ConfigureMessageContract;
use App\Messaging\Contracts\SendMessageContract;

class Email implements ConfigureMessageContract, SendMessageContract
{
    protected $adapter;

    public function __construct($adapter = 'sendgrid')
    {
        $class = "App\\Messaging\\Adapters\\Email\\".ucfirst($adapter);

        $this->adapter = new $class;
    }

    /**
     * Set provider API Key.
     *
     * @param string $key
     */
    public function setApiKey($key)
    {
        $this->adapter->setApiKey($key);
    }

    /**
     * Set user list to whom notification will be sent.
     *
     * @param array $tokens
     */
    public function setMessageTokens($tokens)
    {
        $this->adapter->setMessageTokens($tokens);
    }

    /**
     * Compile API request data.
     */
    public function compileData()
    {
        $this->adapter->compileData(func_get_args());
    }

    /**
     * Send notification.
     *
     * @return string
     */
    public function send()
    {
        return $this->adapter->send();
    }
}
