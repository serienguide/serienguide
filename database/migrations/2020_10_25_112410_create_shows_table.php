<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shows', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('tmdb_id')->nullable()->index();
            $table->unsignedInteger('tvdb_id')->nullable()->index();
            $table->unsignedInteger('guidebox_id')->nullable()->index();
            $table->string('imdb_id')->nullable();

            $table->string('slug')->nullable()->index();
            $table->string('name')->nullable();
            $table->string('name_en')->nullable();
            $table->string('original_language')->nullable();
            $table->string('tagline')->nullable();
            $table->text('overview')->nullable();
            $table->date('first_aired_at')->nullable();
            $table->date('last_aired_at')->nullable();
            $table->string('air_day')->nullable();
            $table->time('air_time')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->unsignedSmallInteger('runtime')->default(0);
            $table->string('homepage')->nullable();
            $table->string('status')->nullable();
            $table->string('type')->nullable();
            $table->boolean('is_anime')->default(false);
            $table->string('poster_path')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->string('video_url')->nullable();
            $table->unsignedSmallInteger('seasons_count')->default(0);
            $table->unsignedMediumInteger('episodes_count')->default(0);

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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shows');
    }
}
