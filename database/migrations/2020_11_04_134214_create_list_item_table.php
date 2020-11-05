<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_item', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('list_id');
            $table->morphs('medium');
            $table->unsignedMediumInteger('rank');

            $table->foreign('list_id')->references('id')->on('lists');

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
        Schema::dropIfExists('list_item');
    }
}
