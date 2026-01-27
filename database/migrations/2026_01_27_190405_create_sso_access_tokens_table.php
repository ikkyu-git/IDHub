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
        Schema::create('sso_access_tokens', function (Blueprint $table) {
            $table->string('id', 255)->primary();
            $table->integer('client_id')->nullable(false);
            $table->string('user_id', 255)->nullable();
            $table->text('scopes')->nullable();
            $table->tinyInteger('revoked')->default(0)->nullable(false);
            $table->dateTime('expires_at')->nullable();
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
        Schema::dropIfExists('sso_access_tokens');
    }
};