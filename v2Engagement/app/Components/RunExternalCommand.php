<?php

namespace App\Components;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class RunExternalCommand
{
    public static function run($command)
    {
        $process = new Process($command);

        try {
            $process->run();

            $process->stop();
        } catch (ProcessFailedException $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}