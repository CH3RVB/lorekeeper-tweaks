<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRotatingStock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_stock', function (Blueprint $table) {
            $table->boolean('is_random_stock')->default(false);
        });

        Schema::table('shops', function (Blueprint $table) {
            //let's just shove all the data into one column lmao.
            $table->string('random_data', 1024)->nullable();
            $table->string('randomized_stock', 10000)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_stock', function(Blueprint $table) {
            $table->dropcolumn('is_random_stock');
        });

        Schema::table('shops', function(Blueprint $table) {
            $table->dropcolumn('random_data');
            $table->dropcolumn('randomized_stock');
        });
    }
}
