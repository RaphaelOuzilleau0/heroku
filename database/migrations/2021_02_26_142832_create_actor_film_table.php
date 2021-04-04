<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActorFilmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('actor_film', function (Blueprint $table) {
            $table->bigInteger('actor_id')->unsigned();
            $table->bigInteger('film_id')->unsigned();
            $table->timestamps();

            $table->foreign('actor_id')->references('id')->on('actors');
            $table->foreign('film_id')->references('id')->on('films');
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
        Schema::dropIfExists('actor_film');
    }
}