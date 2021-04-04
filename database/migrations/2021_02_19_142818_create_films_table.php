<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //https://stackoverflow.com/questions/54431456/php-laravel-pdoexception-general-error-referencing-column-and-referenced-column
        // Problème de foreign key réglé avec
        //                                  |
        //                                  v
        Schema::disableForeignKeyConstraints();
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50);
            $table->string('release_year', 4);
            $table->integer('length');
            $table->text('description');
            $table->string('rating', 5);
            $table->bigInteger('language_id')->unsigned();
            $table->string('special_features', 200)->nullable();
            $table->string('image', 40)->nullable();
            $table->timestamps();

            $table->foreign('language_id')->references('id')->on('languages');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('films');
    }
}