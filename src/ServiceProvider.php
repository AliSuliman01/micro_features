<?php


namespace AliSuliman\MicroFeatures;

use AliSuliman\MicroFeatures\Commands\InitService;
use AliSuliman\MicroFeatures\Http\Middleware\RpcAuthentication;
use AliSuliman\MicroFeatures\Models\MicroModel;
use Illuminate\Contracts\Http\Kernel;
use AliSuliman\MicroFeatures\Http\Middleware\SetLocale;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use AliSuliman\MicroFeatures\Observers\CachedDataObserver;
use AliSuliman\MicroFeatures\Observers\LockedDataObserver;
use AliSuliman\MicroFeatures\Observers\UserTrackingObserver;
use AliSuliman\MicroFeatures\Services\Auth\RemoteAuthGuard;
use AliSuliman\MicroFeatures\Services\Auth\RemoteUserProvider;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(Kernel $kernel)
    {
        $this->register_middlewares($kernel);
        $this->loadMigrations();
        $this->load_routes();
        $this->merge_configs();
        $this->register_publishes();

        MicroModel::observe(CachedDataObserver::class);
        MicroModel::observe(LockedDataObserver::class);
        MicroModel::observe(UserTrackingObserver::class);

        $this->commands([
            InitService::class
        ]);

        Auth::extend('remote_access_token',function(){
            return new RemoteAuthGuard(app(RemoteUserProvider::class), app('request'));
        });
    }

    private function register_middlewares(Kernel $kernel){

        $router = $this->app->make(Router::class);

        $router->aliasMiddleware('rpc_auth', RpcAuthentication::class);

        $kernel->pushMiddleware(SetLocale::class);

    }

    public function load_routes()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/micro_features.php');
        Route::group(['prefix' => 'rpc', 'middleware' => 'rpc_auth'], function () {
            if (file_exists(base_path('routes/rpc.php')))
                $this->loadRoutesFrom(base_path('routes/rpc.php'));
        });
    }

    public function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    public function merge_configs()
    {
        $this->mergeConfigFrom(__DIR__ . '/configs/micro_features.php','micro_features');
    }

    public function register_publishes()
    {
        $this->publishes([
            __DIR__ . '/configs/micro_features.php' => config_path('micro_features.php')
        ], 'micro_feature_config');
    }

}