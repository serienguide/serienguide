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

            $table->unsignedMediumInteger('episode_number')->index();
            $table->unsignedMediumInteger('absolute_number')->default(0)->index();
            $table->string('production_code')->nullable();
            $table->string('name')->nullable();
            $table->string('name_en')->nullable();
            $table->text('overview')->nullable();
            $table->date('first_aired_at')->nullable();
            $table->string('still_path')->nullable();

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
