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
        Schema::create('sso_auth_codes', function (Blueprint $table) {
            $table->string('id', 255)->primary();
            $table->integer('client_id')->nullable(false);
            $table->string('user_id', 255)->nullable();
            $table->text('scopes')->nullable();
            $table->tinyInteger('revoked')->nullable();
            $table->dateTime('expires_at')->nullable(false);
            $table->timestamps();
            $table->string('code_challenge', 255)->nullable();
            $table->string('code_challenge_method', 255)->nullable();
            $table->string('nonce', 255)->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sso_auth_codes');
    }
};