<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActorFilmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actor_films', function (Blueprint $table) {
            //$table->id();
            $table->integer('actor_id');
            $table->integer('film_id');
            $table->primary(array('actor_id', 'film_id'));
            $table->dateTime('created_at');
            $table->dateTime('updated_at');

            $table->foreign('actor_id')->references('id')->on('actors');
            $table->foreign('film_id')->references('id')->on('films');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actor_films');
    }
}
