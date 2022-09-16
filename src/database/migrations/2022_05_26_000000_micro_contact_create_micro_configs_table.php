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
        if(!Schema::hasTable('micro_configs'))
            Schema::create('micro_configs', function (Blueprint $table) {
            $table->id();
            $table->boolean('logging')->default(0);
            $table->boolean('caching_logs')->default(0);
            $table->integer('items_per_page')->nullable();
            $table->integer('max_logging_response_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('micro_configs');
    }
};
