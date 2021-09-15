<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersInventory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::create('users_inventory', function (Blueprint $table) {
           $table->increments('id');
           $table->unsignedInteger('item_id');
           $table->unsignedInteger('user_id');
           $table->unsignedInteger('serial');
         });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
         Schema::dropIfExists('users_inventory');
     }
}
