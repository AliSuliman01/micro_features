<?php


namespace AliSuliman\MicroFeatures\Tests;


use AliSuliman\MicroFeatures\ServiceProvider;
use Sajya\Server\ServerServiceProvider;

class
TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array<int, string>
     */
    public function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
            ServerServiceProvider::class
        ];
    }


    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.debug',true);
        $app['config']->set('database.default','mysql');
        $app['config']->set('database.connections.mysql',[
            'driver' => 'mysql',
            'host' =>  '127.0.0.1',
            'port' =>  '3306',
            'database' =>  'test_db',
            'username' =>  'root',
            'password' =>  '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => 'InnoDB',
            'options' =>  [],
        ]);
    }

}