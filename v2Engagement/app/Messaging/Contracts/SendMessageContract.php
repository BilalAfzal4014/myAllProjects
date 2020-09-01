<?php

namespace App\Messaging\Contracts;

interface SendMessageContract
{
    /**
     * Send notification.
     */
    public function send();
}