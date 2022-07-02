<?php


namespace AliSuliman\P2PRpc;

use AliSuliman\P2PRpc\Http\Middleware\RpcAuthentication;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Route;
use AliSuliman\P2PRpc\Http\Middleware\SetLocale;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(Kernel $kernel)
    {
        $this->register_middlewares($kernel);
        $this->load_routes();
        $this->merge_configs();
        $this->register_publishes();
    }

    private function register_middlewares(Kernel $kernel){

        $router = $this->app->make(Route::class);

        $router->aliasMiddleware('rpc_auth', RpcAuthentication::class);

        $kernel->pushMiddleware(SetLocale::class);

    }

    public function load_routes()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/p2p_rpc.php');
        \Illuminate\Support\Facades\Route::group(['prefix' => 'rpc', 'middleware' => 'rpc_auth'], function () {
            if (file_exists(base_path('routes/rpc.php')))
                $this->loadRoutesFrom(base_path('routes/rpc.php'));
        });
    }

    public function merge_configs()
    {
        $this->mergeConfigFrom(__DIR__ . '/configs/p2p_rpc.php','p2p_rpc');
        $this->mergeConfigFrom(__DIR__ . '/configs/microservices.php','microservices');
    }

    public function register_publishes()
    {
        $this->publishes([
            __DIR__ . '/configs/p2p_rpc.php' => config_path('p2p_rpc.php')
        ], 'p2p_rpc_config');
    }
}