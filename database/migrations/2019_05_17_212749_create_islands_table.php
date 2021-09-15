<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIslandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('islands', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serverName',50)->default("Untitled");
            $table->string('description',1500)->nullable();
            $table->string('thumbnail1', 255)->default("/imgs/games/defaultisland.png");
            $table->string('thumbnail2', 255)->default("/imgs/games/defaultisland.png");
            $table->string('thumbnail3', 255)->default("/imgs/games/defaultisland.png");
            $table->integer('visits')->default(0);
            $table->integer('playersOnline')->default(0);
            $table->integer('maxplayers')->default(10);
            $table->boolean('featured')->default(false);
            $table->boolean('locked')->default(false);
            $table->boolean('copylocked')->default(true);
            $table->boolean('goldonly')->default(false);
            $table->boolean('active')->default(true);
            $table->string('password')->nullable();
            $table->unsignedInteger('creator_id')->default(0);
            $table->longText('script_link')->nullable();
            $table->enum('status', array('wip','alpha','beta','release'))->default('wip');
            $table->enum('genre', array('city','fantasy','horror','scifi','wildwest','building','social','AAA','militry','comedy','medieval','naval','fps','rpg','fighting','sports'))->default('social');
            $table->integer('last_played')->default(0);
            $table->unsignedInteger('created')->default(now()->timestamp);
            $table->unsignedInteger('updated')->default(now()->timestamp);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('islands');
    }
}
