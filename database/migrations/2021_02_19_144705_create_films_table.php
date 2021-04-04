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
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50);
            $table->year('release_year', 4)->nullable();
            $table->integer('length')->length(3)->unsigned()->nullable();
            $table->text('description');
            $table->string('rating', 5)->nullable();
            $table->integer('language_id')->length(3)->unsigned(); // FOREIGN KEY
            $table->string('special_features', 200)->nullable();
            $table->string('image', 40)->nullable();
            $table->timestamp('created_at');

            $table->foreign('language_id')->references('id')->on('languages');
        });
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
