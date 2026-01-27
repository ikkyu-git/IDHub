<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sso_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->string('client_id', 255)->nullable();
            $table->string('client_secret', 255)->nullable();
            $table->text('redirect_uris')->nullable(false);
            $table->timestamps();
            $table->string('logo_uri', 255)->nullable();
            $table->string('policy_uri', 255)->nullable();
            $table->string('tos_uri', 255)->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sso_clients');
    }
};