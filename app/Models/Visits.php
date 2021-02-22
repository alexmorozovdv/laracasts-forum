<?php


namespace App\Models;


use Illuminate\Support\Facades\Redis;

class Visits
{
    protected $thread;

    /**
     * Visits constructor.
     * @param $thread
     */
    public function __construct($thread)
    {
        $this->thread = $thread;
    }

    public function reset()
    {
        Redis::del($this->cacheKey());

        return $this;
    }

    public function count()
    {
        return Redis::get($this->cacheKey()) ?? 0;
    }

    public function record()
    {
        Redis::incr($this->cacheKey());

        return $this;
    }

    public function cacheKey()
    {
        return "threads.{$this->thread->id}.visits";
    }
}
