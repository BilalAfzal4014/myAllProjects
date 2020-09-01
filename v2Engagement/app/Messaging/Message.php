<?php

namespace App\Messaging;

use App\Messaging\Contracts\ConfigureMessageContract;
use App\Messaging\Contracts\SendMessageContract;

class Message implements ConfigureMessageContract, SendMessageContract
{
    /**
     * @var \App\Messaging\Contracts\SendMessageContract
     */
    protected $adapter;

    public function __construct($adapter = '')
    {
        if (!empty($adapter)) {
            $this->setAdapter($adapter);
        }
    }

    /**
     * Set notification adapter to be used.
     *
     * @param string $adapter
     * @param string $provider
     */
    public function setAdapter($adapter, $provider = '')
    {
        $class = "App\\Messaging\\Adapters\\".ucfirst($adapter);
        $this->adapter = !empty($provider) ? (new $class($provider)) : (new $class);
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
        if (func_num_args() == 1) {
            return $this->adapter->compileData(func_get_args()[0]);
        }

        return $this->adapter->compileData(func_get_args());
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