<?php


namespace AliSuliman\MicroFeatures\Http\Procedures;

use AliSuliman\MicroFeatures\Http\Traits\UseConditions;
use AliSuliman\MicroFeatures\Models\CachedData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class QueryBuilderProcedure extends Procedure
{
    use UseConditions;

    public function first(Request $request)
    {
        $query = DB::table($request->index)
            ->when(!$request->withTrashed, fn($query) => $query->where('deleted_at', '=', null));

        $this->setQueryConditions($request->conditions);
        $this->applyConditions($query);

        return $query->first();
    }

    public function get(Request $request)
    {
        $query = DB::table($request->index)
            ->when(!$request->withTrashed, fn($query) => $query->where('deleted_at', '=', null));

        $this->setQueryConditions($request->conditions);
        $this->applyConditions($query);

        if ($request->caching)
            CachedData::query()->create([
                'table' => $request->index,
                'microservice' => $request->microservice
            ]);

        return $query->get();
    }

    public function store(Request $request)
    {
        $columns = Schema::getColumnListing($request->index);

        $id = DB::table($request->index)->insertGetId(collect($request->data)->only($columns)->toArray());

        return DB::table($request->index)->find($id);
    }

    public function update(Request $request)
    {
        $columns = Schema::getColumnListing($request->index);

        $query = DB::table($request->index)->when(!$request->withTrashed, fn($query) => $query->where('deleted_at', '=', null));

        $this->setQueryConditions($request->conditions);
        $this->applyConditions($query);

        return $query->update(collect($request->data)->only($columns)->toArray());
    }

    public function delete(Request $request)
    {
        $query = DB::table($request->index);

        $this->setQueryConditions($request->conditions);
        $this->applyConditions($query);

        return $query->update(['deleted_at' => now()->format('Y-m-d H:i:s')]);
    }
}