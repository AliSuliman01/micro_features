<?php


namespace AliSuliman\MicroFeatures\Jobs;


use AliSuliman\MicroFeatures\Http\Traits\UseConditions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class UpdateCacheJob  extends Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, UseConditions;

    private $table;
    private $data;
    private $keyName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($table, $data, $keyName)
    {
        $this->table = $table;
        $this->data = $data;
        $this->keyName = $keyName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $collection = collect(Cache::get($this->table));
        $collection = $this->applyConditions($collection);
        $updatedCollection = $collection->map(function ($item) {
            return (object)array_merge((array)$item, $this->data);
        });
        Cache::put($this->table, $updatedCollection->merge($collection)->unique($this->keyName)->sortBy($this->keyName)->values());

    }
}