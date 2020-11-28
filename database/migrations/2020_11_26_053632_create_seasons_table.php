<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('show_id')->nullable();
            $table->unsignedInteger('tmdb_id')->nullable()->index();
            $table->unsignedInteger('tvdb_id')->nullable()->index();

            $table->unsignedMediumInteger('season_number')->index();
            $table->text('overview')->nullable();
            $table->date('first_aired_at')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('video_url')->nullable();
            $table->unsignedMediumInteger('episode_count')->default(0);

            $table->unsignedMediumInteger('vote_count')->default(0);
            $table->unsignedDecimal('vote_average', 3, 1)->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('show_id')->references('id')->on('shows');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seasons');
    }
}
