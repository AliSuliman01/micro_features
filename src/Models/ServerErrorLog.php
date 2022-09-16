<?php

namespace AliSuliman\MicroFeatures\Models;

class ServerErrorLog extends MicroModel
{
    public $timestamps = false;
    protected $table = 'server_error_log';
    protected $guarded = [];

}
