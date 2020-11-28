<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('show_id')->nullable();
            $table->unsignedBigInteger('season_id')->nullable();
            $table->unsignedInteger('tmdb_id')->nullable()->index();
            $table->unsignedInteger('tvdb_id')->nullable()->index();
            $table->unsignedInteger('guidebox_id')->nullable()->index();

            $table->unsignedMediumInteger('episode_number')->index();
            $table->unsignedMediumInteger('absolute_number')->default(0)->index();
            $table->string('production_code')->nullable();
            $table->string('name')->nullable();
            $table->string('name_en')->nullable();
            $table->string('name_full')->nullable();
            $table->text('overview')->nullable();
            $table->date('first_aired_at')->nullable();
            $table->date('first_aired_de_at')->nullable();
            $table->date('first_aired_en_at')->nullable();
            $table->string('still_path')->nullable();
            $table->string('video_url')->nullable();

            $table->unsignedMediumInteger('vote_count')->default(0);
            $table->unsignedDecimal('vote_average', 3, 1)->default(0);
            $table->unsignedMediumInteger('tmdb_vote_count')->default(0);
            $table->unsignedDecimal('tmdb_vote_average', 3, 1)->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('show_id')->references('id')->on('shows');
            $table->foreign('season_id')->references('id')->on('seasons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('episodes');
    }
}
