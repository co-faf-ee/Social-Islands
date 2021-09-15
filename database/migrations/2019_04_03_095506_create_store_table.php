<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store', function (Blueprint $table) {
          $table->increments('id');
          $table->string('item_name', 255);
          $table->string('item_description', 255);
          $table->unsignedInteger('price');
          $table->boolean('rare')->default(false);
          $table->unsignedInteger('rare_quantity')->default(0);
          $table->unsignedInteger('rare_quantity_original')->default(0);
          $table->boolean('offsale')->default(true);
          $table->boolean('gold_only')->default(false);
          $table->unsignedInteger('sold')->default(0);
          $table->unsignedInteger('created')->default(now()->timestamp);
          $table->unsignedInteger('last_updated')->default(now()->timestamp);
          $table->string('img_url',255)->default("/imgs/avatars/avatar-default.png"); // CHANGE TO 450X800 BLANK TRANS
          $table->string('layer',20)->default("Body");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store');
    }
}
