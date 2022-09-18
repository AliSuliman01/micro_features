<?php


use AliSuliman\MicroFeatures\Helpers\Constants;
use AliSuliman\MicroFeatures\Http\Middleware\RpcAuthentication;
use AliSuliman\MicroFeatures\Http\Procedures\QueryBuilderProcedure;
use AliSuliman\MicroFeatures\Http\Procedures\QueueProcedure;
use Illuminate\Support\Facades\Route;

Route::prefix('rpc')->middleware(RpcAuthentication::class)->group(function () {

    Route::rpc(Constants::REMOTE_BUILDER_INDEX, [QueryBuilderProcedure::class]);
    Route::rpc(Constants::QUEUE_INDEX, [QueueProcedure::class]);

});
