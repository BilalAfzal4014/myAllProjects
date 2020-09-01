<?php

namespace App\Console;

use App\ConsoleJobs;

class ConsoleInfo
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $item;

    /**
     * ConsoleInfo constructor.
     *
     * @param string $key
     */
    public function __construct($key)
    {
        try {
            $this->item = ConsoleJobs::where('name', $key)->firstOrFail();
        } catch (\Exception $exception) {
            $this->item = new ConsoleJobs();
            $this->name = $key;
        }
    }

    public function getItem()
    {
        return $this->item;
    }

    /**
     * Add job info to database.
     *
     * @param string $data
     *
     * @return mixed
     */
    public function add($data)
    {
        if ($this->item->id) {
            return $this->getItem();
        }

        $this->item->name = $this->name;
        $this->item->enabled = $data['enabled'];
        $this->item->interval = $data['interval'];
        $this->item->save();
    }
}