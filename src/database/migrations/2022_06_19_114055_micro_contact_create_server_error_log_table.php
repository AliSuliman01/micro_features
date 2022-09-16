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
        try{
            Schema::create('server_error_log', function (Blueprint $table) {
                $table->id();
                $table->longText('message');
                $table->longText('context');
                $table->string('level')->index();
                $table->string('level_name');
                $table->string('channel')->index();
                $table->string('record_datetime');
                $table->longText('extra')->nullable();
                $table->longText('formatted');

                $table->string('remote_addr')->nullable();
                $table->string('user_agent')->nullable();
                $table->dateTime('created_at')->nullable();
            });
        }catch (\Exception $e){
                $this->down();
                throw $e;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('server_error_log');
    }
};