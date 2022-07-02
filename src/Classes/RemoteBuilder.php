<?php


namespace AliSuliman\P2PRpc\Classes;


use AliSuliman\P2PRpc\RemoteModels\RemoteModel;

class RemoteBuilder
{
    private $remoteModel;
    private $curl;

    protected $trash = false;
    protected $table;
    protected $wheres = [];
    protected $whereIns = [];

    public function __construct(RemoteModel $remoteModel)
    {
        $this->curl = new Curl($remoteModel->base_url() . '/' . Urls::REMOTE_BUILDER_URI);

        $this->remoteModel = $remoteModel;
    }

    public function withTrash()
    {
        $this->trash = true;
        return $this;
    }

    // get first record that fulfills all wheres

    public function first($columns=['*'])
    {
        return $this->curl->call("first",[
            'table' => $this->table,
            'wheres' => $this->wheres,
            'whereIns' => $this->whereIns,
            'columns' => $columns,
            'trash' => $this->trash,
        ]);
    }

    public function get($columns=['*'])
    {
        return $this->curl->call('get', [
            'table' => $this->remoteModel->table,
            'wheres' => $this->wheres,
            'whereIns' => $this->whereIns,
            'columns' => $columns,
            'trash' => $this->trash,
        ]);
    }

    public function find($id)
    {
        return $this->curl->call('find', [
            'table' => $this->remoteModel->table,
            'id' => $id,
            'trash' => $this->trash,
        ]);
    }

    // find or create new record

    public function findOrCreate($data = [])
    {
        return $this->curl->call('findOrCreate', [
            'table' => $this->table,
            'wheres' => $this->wheres,
            'whereIns' => $this->whereIns,
            'data' => $data,
            'trash' => $this->trash,
        ]);
    }

    // update or insert new record

    public function updateOrInsert($data = [])
    {
        return $this->curl->call('updateOrInsert', [
            'table' => $this->table,
            'wheres' => $this->wheres,
            'whereIns' => $this->whereIns,
            'data' => $data,
            'trash' => $this->trash,
        ]);
    }

    // save many records

    public function saveMany($data)
    {
        return $this->curl->call('saveMany', [
            'table' => $this->table,
            'data' => $data,
            'trash' => $this->trash,
        ]);
    }

    // create new record

    public function create($data)
    {
        return $this->curl->call('create', [
            'table' => $this->table,
            'data' => $data,
            'trash' => $this->trash,
        ]);
    }

    // update records

    public function update($data)
    {
        return $this->curl->call('update', [
            'table' => $this->table,
            'wheres' => $this->wheres,
            'whereIns' => $this->whereIns,
            'data' => $data,
            'trash' => $this->trash,
        ]);
    }

    // soft delete records

    public function softDelete()
    {
        return $this->curl->call('softDelete', [
            'table' => $this->table,
            'wheres' => $this->wheres,
            'whereIns' => $this->whereIns,
            'trash' => $this->trash,
        ]);
    }


    // force delete records
    public function forceDelete()
    {
        return $this->curl->call('forceDelete', [
            'table' => $this->table,
            'wheres' => $this->wheres,
            'whereIns' => $this->whereIns,
            'trash' => $this->trash,
        ]);
    }

    public function where($column, $operation, $value)
    {
        $this->wheres[] = [$column, $operation, $value];
        return $this;
    }

    public function whereIn($column, $array)
    {
        $this->whereIns[] = [$column, $array];
        return $this;
    }
}