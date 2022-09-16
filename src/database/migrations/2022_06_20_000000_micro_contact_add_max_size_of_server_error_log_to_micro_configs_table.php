<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('micro_configs',function (Blueprint $table){
            $table->unsignedBigInteger('max_size_of_server_error_log')->default(1e5);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('micro_configs',function (Blueprint $table){
            $table->dropColumn('max_size_of_server_error_log');
        });
    }
};
