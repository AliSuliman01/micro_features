<?php


namespace AliSuliman\P2PRpc\RemoteModels;


use AliSuliman\P2PRpc\classes\RemoteBuilder;
use Illuminate\Support\Str;

abstract class RemoteModel
{
    public $table;

    public function __construct()
    {
        $this->table = $this->table ?? Str::snake(Str::pluralStudly(class_basename($this)));
    }

    public abstract function base_url();

    public static function query(){
        $remote_model = new static();

        return new RemoteBuilder($remote_model);
    }

}