<?php


namespace Ramaaz\MicroContact\Observers;


use AliSuliman\MicroFeatures\Exceptions\Exception;
use AliSuliman\MicroFeatures\Helpers\StatusCode;
use Illuminate\Database\Eloquent\Model;

class LockedDataObserver
{
    public function updating(Model $model){
        if (isset($model->is_locked_by_admin_for_update) && $model->is_locked_by_admin_for_update)
            throw new Exception(__('locked_update'),StatusCode::UNAUTHORIZED);
    }
    public function deleting(Model $model){
        if (isset($model->is_locked_by_admin_for_delete) && $model->is_locked_by_admin_for_delete)
            throw new Exception(__('locked_delete'),StatusCode::UNAUTHORIZED);
    }
}