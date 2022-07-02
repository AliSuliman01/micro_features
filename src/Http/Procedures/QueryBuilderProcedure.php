<?php


namespace AliSuliman\P2PRpc\Http\Procedures;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class QueryBuilderProcedure extends Procedure
{
    private function applyWheres(&$query, $wheres): void
    {

        foreach ($wheres as $where) {
            $query = $query->where($where[0], $where[1], $where[2]);
        }

    }

    protected function applyWhereIns(&$query, $whereIns): void
    {

        foreach ($whereIns as $where) {
            $query = $query->whereIn($where[0], $where[1]);
        }

    }

    public function first(Request $request)
    {
        $query = DB::table($request->table)
            ->when(!$request->trash, fn ($query) => $query->where('deleted_at', '=', null))
            ->select($request->columns);

        $this->applyWheres($query, $request->wheres);
        $this->applyWhereIns($query, $request->whereIns);

        return $query->first();
    }

    public function get(Request $request)
    {
        $query = DB::table($request->table)
            ->when(!$request->trash, fn ($query) => $query->where('deleted_at', '=', null))
            ->select($request->columns);

        $this->applyWheres($query, $request->wheres);
        $this->applyWhereIns($query, $request->whereIns);

        return $query->get();
    }

    public function find(Request $request)
    {
        $query = DB::table($request->table)
            ->when(!$request->trash, fn ($query) => $query->where('deleted_at', '=', null));

        return $query->find($request->id);
    }

    public function create(Request $request)
    {
        $columns = Schema::getColumnListing($request->table);

        $id = DB::table($request->table)->insertGetId(collect($request->data)->only($columns)->toArray());

        return DB::table($request->table)->find($id);
    }

    public function findOrCreate(Request $request)
    {
        $query = DB::table($request->table)
            ->when(!$request->trash, fn ($query) => $query->where('deleted_at', '=', null));

        $this->applyWheres($query, $request->wheres);
        $this->applyWhereIns($query, $request->whereIns);

        $result = $query->first();

        if (!$result) {
            $columns = Schema::getColumnListing($request->table);
            $id = DB::table($request->table)->insertGetId(collect(array_merge($request->wheres, $request->data))->only($columns)->toArray());
            $result = DB::table($request->table)->find($id);
        }

        return $result;
    }

    public function updateOrInsert(Request $request)
    {
        $columns = Schema::getColumnListing($request->table);
        return DB::table($request->table)->updateOrInsert($request->wheres, collect($request->data)->only($columns)->toArray());
    }

    public function saveMany(Request $request)
    {
        $columns = Schema::getColumnListing($request->table);
        $data = collect($request->data)->map(function ($record) use ($columns) {
            return collect($record)->only($columns)->toArray();
        })->toArray();
        return DB::table($request->table)->insert($data);
    }

    public function update(Request $request)
    {
        $columns = Schema::getColumnListing($request->table);
        $query = DB::table($request->table)
            ->when(!$request->trash, fn ($query) => $query->where('deleted_at', '=', null));

        $this->applyWheres($query, $request->wheres);
        $this->applyWhereIns($query, $request->whereIns);

        return $query->update(collect($request->data)->only($columns)->toArray());
    }

    public function forceDelete(Request $request)
    {
        $query = DB::table($request->table);

        $this->applyWheres($query, $request->wheres);
        $this->applyWhereIns($query, $request->whereIns);

        return $query->delete();
    }

    public function softDelete(Request $request)
    {
        $query = DB::table($request->table);

        $this->applyWheres($query, $request->wheres);
        $this->applyWhereIns($query, $request->whereIns);

        return $query->update(['deleted_at' => now()->format('Y-m-d H:i:s')]);
    }
}