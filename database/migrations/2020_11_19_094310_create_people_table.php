<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('imdb_id')->nullable();

            $table->string('name');
            $table->string('slug');
            $table->date('birthday_at')->nullable();
            $table->date('deathday_at')->nullable();
            $table->string('known_for_department')->nullable();
            $table->unsignedTinyInteger('gender')->nullable();
            $table->text('biography')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('profile_path')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->string('video_url')->nullable();
            $table->string('homepage')->nullable();
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
        Schema::dropIfExists('people');
    }
}
