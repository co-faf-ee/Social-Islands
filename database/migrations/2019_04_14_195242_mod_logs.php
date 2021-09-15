<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('mod_logs', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('mod_id');
        $table->unsignedInteger('user_id');
        $table->string('action',50);
        $table->unsignedInteger('time')->default(now()->timestamp);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mod_logs');
    }
}
