<?php

namespace App\Console;

trait ConsoleOutputs
{
    /**
     * @var array
     */
    protected $outputLog;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $item;

    /**
     * @return void
     */
    protected function getCommandDetails()
    {
        $name = str_replace(__NAMESPACE__ . '\\Commands\\', '', __CLASS__);
        $this->item = (new ConsoleInfo($name))->getItem();
    }

    /**
     * Push output to log.
     *
     * @param string $output
     */
    protected function addToOutput($output)
    {
        if (empty($this->outputLog)) {
            $this->outputLog = [];
        }

        $this->outputLog[] = $output;
    }

    /**
     * @return void
     */
    protected function showOutput()
    {
        $this->comment(implode('\n', $this->outputLog));
    }

    /**
     * Check total instances.
     *
     * @return bool
     */
    protected function checkInstances()
    {
        $instance_count = $this->item->instance_count;
        $instances = config('jobs.list.'.$this->item->name.'.instances');

        return ($instance_count < $instances) ? true : false;
    }

    /**
     * Increment instance count.
     */
    protected function incrementInstanceCount()
    {
        $this->item->instance_count +=1;
        $this->item->save();
    }

    /**
     * Decrement instance count.
     */
    protected function decrementInstanceCount()
    {
        $this->item->instance_count -=1;
        $this->item->save();
    }

    /**
     * Check retry limit. If limit not reached, increment retry limit.
     *
     * @return bool
     */
    protected function checkRetryLimit()
    {
        $retry_count = $this->item->retry_count;
        $retry_limit = config('jobs.list.'.$this->item->name.'.retry_limit');

        return ($retry_count >= $retry_limit) ? false : true;
    }

    /**
     * Increment retry limit.
     */
    protected function incrementRetryLimit()
    {
        $this->item->retry_count +=1;
        $this->item->save();
    }

    /**
     * Increment retry limit.
     */
    protected function resetRetryLimit()
    {
        $this->item->retry_count = 0;
        $this->item->save();
    }
}