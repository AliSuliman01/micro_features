<?php

namespace AliSuliman\MicroFeatures\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class CachedData extends MicroModel
{
    use SoftDeletes;

    protected $table = 'cached_data';

    protected $fillable = [
        'table',
        'microservice'
    ];

}
