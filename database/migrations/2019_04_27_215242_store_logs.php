<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StoreLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('store_logs', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('item_id');
        $table->unsignedInteger('user_id');
        $table->Integer('seller_id')->nullable();
        $table->unsignedInteger('price');
        $table->unsignedInteger('time');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_logs');
    }
}
