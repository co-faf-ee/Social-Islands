<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StoreCustomUploads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('store_custom_uploads', function (Blueprint $table) {
          $table->increments('id');
          $table->unsignedInteger('user_id');
          $table->string('item_name', 255);
          $table->string('item_description', 255);
          $table->unsignedInteger('price');
          $table->boolean('offsale')->default(true);
          $table->boolean('gold_only')->default(false);
          $table->unsignedInteger('created')->default(now()->timestamp);
          $table->unsignedInteger('last_updated')->default(now()->timestamp);
          $table->string('img_url',255); // CHANGE TO 450X800 BLANK TRANS
          $table->enum('layer', array('trousers','shirt'));
          $table->enum('status', array('rejected','pending','accepted'))->default("pending");
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_custom_uploads');
    }
}
