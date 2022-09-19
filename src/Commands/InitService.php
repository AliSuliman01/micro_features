<?php

namespace AliSuliman\MicroFeatures\Commands;

use AliSuliman\MicroFeatures\Exceptions\Exception;
use AliSuliman\MicroFeatures\RemoteModels\Service;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class InitService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'service:init {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize the service.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $myUrl = config('app.url');

        Cache::put('serviceInfo', [
            'name' => $this->argument('name'),
            'url' => $myUrl
        ]);

        if (!$service = Service::query()->where('name','=',$this->argument('name'))->first()) {
            Service::query()->store([
                'name' => $this->argument('name'),
                'url' => $myUrl
            ]);
            $this->info("Registered successfully!");
        }
        else{
            $this->error("Already existed!");
        }

        return 0;
    }
}
