<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title', 255);
            $table->text('description');
            $table->year('release_year', 4)->nullable();

            $table->smallInteger('language_id')->length(3)->unsigned(); //
            $table->smallInteger('original_language_id')->length(3)->unsigned()->nullable(); //
            $table->smallInteger('rental_duration')->length(3)->default(3)->unsigned(); //
            $table->decimal('rental_rate',4 ,2)->default(4.99);
            $table->smallInteger('length')->length(5)->unsigned()->nullable(); //
            $table->decimal('replacement_cost', 5, 2)->default(19.99);

            $table->enum('rating', ['G','PG','PG-13','R','NC-17'])->nullable()->default('G');
            $table->set('special_features', ['Trailers','Commentaries','Deleted Scenes','Behind the Scenes'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
    }
}
