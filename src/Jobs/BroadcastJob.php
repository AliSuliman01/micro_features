<?php


namespace AliSuliman\MicroFeatures\Jobs;


use AliSuliman\MicroFeatures\Http\Traits\UseConditions;
use AliSuliman\MicroFeatures\RemoteModels\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BroadcastJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,UseConditions;

    private $remoteJob;
    private $services;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Job $job, $services = ['*'])
    {
        $this->remoteJob = $job;
        $this->services = $services;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (in_array('*', $this->services)){
            $this->services = Service::getAllServicesName();
        }

        foreach ($this->services as $serviceName){
            rpc($serviceName,'queue','push',[
                'job_class' => get_class($this->remoteJob),
                'job_params' => $this->remoteJob->getProps()
            ]);
        }
    }
}
