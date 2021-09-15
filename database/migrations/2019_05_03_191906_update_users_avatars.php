<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersAvatars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('users_avatars', function (Blueprint $table) {
        $table->Integer('hair_id')->default(-1);
        $table->Integer('eyewear_id')->default(-1);
        $table->Integer('background_id')->default(-1);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_avatars', function(Blueprint $table){
          $table->dropColumn('hair_id','eyewear_id','background_id');
        });
    }
}
