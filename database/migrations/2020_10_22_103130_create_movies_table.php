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
            $table->unsignedBigInteger('collection_id')->nullable();
            $table->unsignedInteger('tmdb_id')->nullable();
            $table->string('imdb_id')->nullable();

            $table->string('slug')->nullable()->index();
            $table->string('name')->nullable();
            $table->string('name_en')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('tagline')->nullable();
            $table->text('overview')->nullable();
            $table->date('released_at')->nullable();
            $table->unsignedSmallInteger('runtime')->default(0);
            $table->string('homepage')->nullable();
            $table->string('status')->nullable();
            $table->unsignedSmallInteger('budget')->default(0);
            $table->unsignedSmallInteger('revenue')->default(0);
            $table->string('poster_path')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();

            $table->timestamps();

            $table->foreign('collection_id')->references('id')->on('movie_collection');

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
