<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StoreSelling extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('store_reselling', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('item_id');
        $table->unsignedInteger('user_id');
        $table->unsignedInteger('serial');
        $table->unsignedInteger('price');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_reselling');
    }
}
