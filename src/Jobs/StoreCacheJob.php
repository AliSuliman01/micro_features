<?php


namespace AliSuliman\MicroFeatures\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class StoreCacheJob  extends Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $table;
    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($table, $data)
    {
        $this->table = $table;
        $this->data = $data;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Cache::put($this->table,collect(Cache::get($this->table))->merge($this->data));
    }
}