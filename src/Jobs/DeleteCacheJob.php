<?php


namespace AliSuliman\MicroFeatures\Jobs;


use AliSuliman\MicroFeatures\Http\Traits\UseConditions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class DeleteCacheJob extends Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,UseConditions;

    private $table;
    private $keyName;
    private $keyValue;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($table)
    {
        $this->table = $table;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $collection = collect(Cache::get($this->table));
        $newCollection = $this->applyReverseConditions($collection);
        Cache::put($this->table, $newCollection);
    }
}