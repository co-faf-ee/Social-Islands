<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BanLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('ban_logs', function (Blueprint $table) {
        $table->increments('id');
        $table->Integer('mod_id')->default(1);
        $table->Integer('user_id')->default(1);
        $table->Integer('length')->default(-1);
        $table->string('reason',650);
        $table->unsignedInteger('start_time');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ban_logs');
    }
}
