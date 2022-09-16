<?php


namespace Ramaaz\MicroContact\Observers;

use AliSuliman\MicroFeatures\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class UserTrackingObserver
{

    public static function creating(Model $model)
    {
        Schema::hasColumn($model->getTable(), 'created_by_user_id') ? $model->created_by_user_id = Auth::id() : null;
    }

    public static function updating(Model $model)
    {
        Schema::hasColumn($model->getTable(), 'updated_by_user_id') ? $model->updated_by_user_id = Auth::id() : null;

    }

    public static function deleting(Model $model)
    {
        Schema::hasColumn($model->getTable(), 'deleted_by_user_id') ? $model->deleted_by_user_id = Auth::id() : null;
    }

}
