<?php


namespace AliSuliman\MicroFeatures\Observers;


use AliSuliman\MicroFeatures\Jobs\BroadcastJob;
use AliSuliman\MicroFeatures\Jobs\DeleteCacheJob;
use AliSuliman\MicroFeatures\Jobs\StoreCacheJob;
use AliSuliman\MicroFeatures\Jobs\UpdateCacheJob;
use AliSuliman\MicroFeatures\Models\CachedData;
use Illuminate\Database\Eloquent\Model;

class CachedDataObserver
{
    public function created(Model $model)
    {
        $microservices = CachedData::query()->where('table', $model->getTable())->pluck('microservice')->toArray();
        if (count($microservices))
            dispatch(new BroadcastJob(new StoreCacheJob($model->getTable(), $model), $microservices));
    }

    public function updated(Model $model)
    {
        $microservices = CachedData::query()->where('table', $model->getTable())->pluck('microservice')->toArray();
        if (count($microservices))
            dispatch(new BroadcastJob(
                (new UpdateCacheJob($model->getTable(), $model, $model->getKeyName()))
                    ->where($model->getKeyName(),'=',$model->getKey())
                , $microservices));
    }

    public function deleted(Model $model)
    {
        $microservices = CachedData::query()->where('table', $model->getTable())->pluck('microservice')->toArray();
        if (count($microservices))
            dispatch(new BroadcastJob((new DeleteCacheJob($model->getTable()))->where($model->getKeyName(),'=',$model->getKey()), $microservices));
    }

}