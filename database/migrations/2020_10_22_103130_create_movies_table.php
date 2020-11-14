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
            $table->unsignedSmallInteger('budget')->nullable();
            $table->unsignedSmallInteger('revenue')->nullable();

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
