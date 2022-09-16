<?php


namespace AliSuliman\MicroFeatures\Builders;


use AliSuliman\MicroFeatures\Helpers\Constants;
use AliSuliman\MicroFeatures\Helpers\RPC;
use AliSuliman\MicroFeatures\Http\Traits\UseConditions;
use Illuminate\Support\Facades\Cache;

class RemoteQueryBuilder extends QueryBuilder
{
    protected $serviceName;
    protected $index;
    protected $caching = false;

    public function __construct($serviceName, $index)
    {
        $this->serviceName = $serviceName;
        $this->index = $index;
    }
    public function caching($value = true)
    {
        $this->caching = $value;
        return $this;
    }
    public function first()
    {
        return (new RPC($this->serviceName,Constants::REMOTE_BUILDER_INDEX))
            ->call('first',[
                'index' =>$this->index,
                'conditions' => $this->getQueryConditions(),
                'withTrashed' => $this->withTrashed
            ]);
    }

    public function get()
    {
        $collection = collect((new RPC($this->serviceName,Constants::REMOTE_BUILDER_INDEX))
            ->call('get',[
                'index' =>$this->index,
                'conditions' => $this->getQueryConditions(),
                'withTrashed' => $this->withTrashed,
                'caching' => $this->caching,
            ]));

        if ($this->caching)
            Cache::put($this->index, $collection);

        return $collection;
    }

    public function store($data){
        return (new RPC($this->serviceName,Constants::REMOTE_BUILDER_INDEX))
            ->call('store',[
                'index' =>$this->index,
                'data' => $data,
            ]);
    }

    public function update($data){
        return (new RPC($this->serviceName,Constants::REMOTE_BUILDER_INDEX))
            ->call('update',[
                'index' =>$this->index,
                'data' => $data,
                'withTrashed' => $this->withTrashed,
                'conditions' => $this->getQueryConditions(),
            ]);
    }

    public function delete(){
        return (new RPC($this->serviceName,Constants::REMOTE_BUILDER_INDEX))
            ->call('delete',[
                'index' =>$this->index,
                'withTrashed' => $this->withTrashed,
                'conditions' => $this->getQueryConditions(),
            ]);
    }

}