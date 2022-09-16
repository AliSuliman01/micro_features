<?php


namespace AliSuliman\MicroFeatures\Jobs;


use AliSuliman\MicroFeatures\Http\Traits\UseConditions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteJob  extends Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, UseConditions;

    private $table;
    private $keyName;
    private $keyValue;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($table, $keyName, $keyValue)
    {
        $this->table = $table;
        $this->keyValue = $keyValue;
        $this->keyName = $keyName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}