<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_providers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->morphs('provider');
            $table->string('token')->nullable();
            $table->string('token_secret')->nullable();
            $table->string('refresh_token')->nullable();
            $table->unsignedInteger('expires_in')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->dateTime('synced_at')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oauth_providers');
    }
}
