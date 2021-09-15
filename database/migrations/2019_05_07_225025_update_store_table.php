<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('store', function (Blueprint $table) {
        if (!Schema::hasColumn('store', 'creator_id')) {
          $table->Integer('creator_id')->default(-1);
        }

        if (!Schema::hasColumn('store', 'hidden')) {
          $table->Integer('hidden')->default(0);
        }

        if (!Schema::hasColumn('store', 'craftable')) {
          $table->Integer('craftable')->default(0);
        }    
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store', function(Blueprint $table){
          if (Schema::hasColumn('store', 'creator_id')) {
            $table->dropColumn('creator_id');
          }

          if (Schema::hasColumn('store', 'hidden')) {
            $table->dropColumn('hidden');
          }

          if (Schema::hasColumn('store', 'craftable')) {
            $table->dropColumn('craftable');
          }
        });
    }
}
