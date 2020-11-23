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

            $table->unsignedInteger('tmdb_id')->nullable();

            $table->string('slug')->index();
            $table->string('title');
            $table->string('title_en');
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('tagline')->nullable();
            $table->text('overview')->nullable();
            $table->date('released_at')->nullable();
            $table->unsignedSmallInteger('runtime')->nullable();
            $table->string('homepage')->nullable();
            $table->unsignedTinyInteger('status')->nullable();
            $table->unsignedSmallInteger('budget')->default(0);
            $table->unsignedSmallInteger('revenue')->default(0);
            $table->string('poster_path')->nullable();
            $table->string('backdrop_path')->nullable();

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
        Schema::dropIfExists('movies');
    }
}
