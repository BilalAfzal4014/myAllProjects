<?php

namespace App\Messaging\Contracts;

interface ConfigureMessageContract
{
    /**
     * Set provider API Key.
     *
     * @param string $key
     */
    public function setApiKey($key);

    /**
     * Set user list to whom notification will be sent.
     *
     * @param array $tokens
     */
    public function setMessageTokens($tokens);

    /**
     * Compile API request data.
     */
    public function compileData();
}