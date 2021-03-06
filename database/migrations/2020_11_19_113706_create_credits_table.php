<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->unsignedBigInteger('person_id');
            $table->string('credit_type')->nullable();
            $table->string('department')->nullable();
            $table->string('job')->nullable();
            $table->string('character')->nullable();
            $table->unsignedSmallInteger('order')->nullable();

            $table->timestamps();

            $table->foreign('person_id')->references('id')->on('people');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credits');
    }
}
