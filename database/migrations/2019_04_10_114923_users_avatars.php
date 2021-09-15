<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersAvatars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('users_avatars', function (Blueprint $table) {
          $table->increments('id');
          $table->unsignedInteger('user_id');
          $table->Integer('gear_id')->default(-1);
          $table->Integer('top_hat_id')->default(-1);
          $table->Integer('bottom_hat_id')->default(-1);
          $table->Integer('mask_id')->default(-1);
          $table->Integer('eyes_id')->default(-1);
          $table->Integer('mouth_id')->default(-1);
          $table->Integer('torso_id')->default(-1);
          $table->Integer('shirt_id')->default(-1);
          $table->Integer('trousers_id')->default(-1);
          $table->Integer('skin_id')->default(-1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_avatars');
    }
}
