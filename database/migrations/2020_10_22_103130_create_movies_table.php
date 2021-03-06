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
            $table->unsignedInteger('guidebox_id')->nullable()->index();
            $table->string('imdb_id')->nullable()->index();

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
            $table->unsignedInteger('budget')->default(0);
            $table->unsignedInteger('revenue')->default(0);
            $table->string('poster_path')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->string('video_url')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();

            $table->unsignedMediumInteger('vote_count')->default(0);
            $table->unsignedDecimal('vote_average', 3, 1)->default(0);
            $table->unsignedMediumInteger('tmdb_vote_count')->default(0);
            $table->unsignedDecimal('tmdb_vote_average', 3, 1)->default(0);
            $table->unsignedMediumInteger('tmdb_trending')->default(999999);
            $table->unsignedDecimal('tmdb_popularity', 10, 6)->default(0);

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
