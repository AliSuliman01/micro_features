<?php


use AliSuliman\P2PRpc\Classes\Urls;
use AliSuliman\P2PRpc\Http\Middleware\RpcAuthentication;
use AliSuliman\P2PRpc\Http\Procedures\QueryBuilderProcedure;
use Illuminate\Support\Facades\Route;

Route::rpc(Urls::REMOTE_BUILDER_URI, [QueryBuilderProcedure::class])->middleware(RpcAuthentication::class);
