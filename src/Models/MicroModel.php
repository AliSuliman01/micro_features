<?php


namespace AliSuliman\MicroFeatures\Models;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MicroModel extends Model
{
    public static function scopeWhereInMultiple(Builder $query, array $columns, array $values)
    {
        collect($values)
            ->transform(function ($v) use ($columns) {
                $clause = [];
                foreach ($columns as $index => $column) {
                    $clause[] = [$column, '=', $v[$index]];
                }
                return $clause;
            })
            ->each(function ($clause, $index) use ($query) {
                $query->where($clause, null, null, $index === 0 ? 'and' : 'or');
            });

        return $query;
    }

    public function updateRelation($relation, $data)
    {
        DB::transaction(function () use ($relation, $data) {
            if (is_object($data)) $data = [$data];
            $ids = [];
            foreach ($data as $item) {
                if (isset($item['id'])) {
                    $this->{$relation}()->where('id', $item['id'])->update(array_null_filter($item));
                    array_push($ids, $item['id']);
                } else {
                    $model = $this->{$relation}()->create(array_null_filter($item));
                    array_push($ids, $model->id);
                }
            }
            $this->{$relation}()->whereNotIn('id', $ids)->delete();
        });
        return $this->{$relation}()->get();
    }
}