<?php


namespace AliSuliman\MicroFeatures\RemoteModels;


use AliSuliman\MicroFeatures\Exceptions\Exception;
use AliSuliman\MicroFeatures\Interfaces\ShouldBeCached;

class Service extends RemoteModel implements ShouldBeCached
{
    public static function originServiceName():string
    {
        return 'logs';
    }

    public static function getServiceUrl($serviceName)
    {
        if ($serviceName == 'logs')
            return config('micro_features.services_center_url');

        $remoteService = self::query()->where('name', '=', $serviceName)->first();

        if (!$remoteService)
            throw new Exception(__('database.null_value', ['column_name' => 'service_name', 'column_value' => $serviceName]));

        return $remoteService->url;
    }

    public static function getAllServicesName()
    {
        return self::query()->get()->pluck('name');
    }
}