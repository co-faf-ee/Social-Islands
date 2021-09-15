<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StoreComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('store_comments', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('item_id');
        $table->unsignedInteger('user_id');
        $table->string('comment',500);
        $table->unsignedInteger('time_sent');
        $table->boolean('sticky')->default(false);
        $table->unsignedInteger('likes')->default(0);
        $table->unsignedInteger('dislikes')->default(0);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_comments');
    }
}
