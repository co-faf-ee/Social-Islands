<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('password', 255);
            $table->string('email');
            $table->enum('power', array('member','artist','moderator','administrator','engineer','senior_engineer'))->default('member');
            $table->boolean('verified')->default(false);
            $table->integer('cash')->default(100);
            $table->boolean('vip')->default(false);
            $table->boolean('banned')->default(false);
            $table->unsignedInteger('banned_till')->default(0);
            $table->integer('chatSent')->default(0);
            $table->unsignedInteger('last_online')->default(now()->timestamp);
            $table->unsignedInteger('joined')->default(now()->timestamp);
            $table->unsignedInteger('daily_cash')->default(now()->timestamp);
            $table->string('avatar_url',255)->default("/imgs/avatars/avatar-reset.png");
            $table->string('IP',35);
            $table->string('Bio',375)->default("Hey there!");
            $table->string('remember_token', 100)->nullable();
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
