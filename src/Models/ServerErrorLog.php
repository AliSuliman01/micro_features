<?php

namespace Ramaaz\MicroContact\Modules\ServerErrorLog\Model;

use AliSuliman\MicroFeatures\Models\MicroModel;

class ServerErrorLog extends MicroModel
{
    public $timestamps = false;
    protected $table = 'server_error_log';
    protected $guarded = [];

}
