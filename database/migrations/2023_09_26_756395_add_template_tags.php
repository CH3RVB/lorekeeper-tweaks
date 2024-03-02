<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTemplateTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // This table keeps track of template tags.
        // Template tags can be implemented/added using this table similarly to item tags.
        Schema::create('template_tags', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('tag')->index();

            // This will hold the data required for using/displaying this template.
            // Note that the forms for editing the template data will also have 
            // to be created yourself.
            $table->text('data')->nullable()->default(null);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('template_tags');
    }
}
