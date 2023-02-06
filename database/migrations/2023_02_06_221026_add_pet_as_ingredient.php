<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPetAsIngredient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { //we're adding pets to the DB table 
        //important because it really doesn't like it when you make a new ingredient without adding it in the db
        DB::statement("ALTER TABLE collection_ingredients MODIFY COLUMN ingredient_type ENUM('Item', 'Pet')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
