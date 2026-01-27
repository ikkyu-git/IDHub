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
        Schema::create('sso_consents', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 255)->nullable();
            $table->integer('client_id')->nullable(false);
            $table->text('scopes')->nullable();
            $table->dateTime('granted_at')->nullable();
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
        Schema::dropIfExists('sso_consents');
    }
};