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
        Schema::create('persistent_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 255)->nullable();
            $table->string('type', 255)->nullable();
            $table->string('title', 255)->nullable();
            $table->text('message')->nullable(false);
            $table->text('data')->nullable();
            $table->tinyInteger('require_action')->nullable();
            $table->tinyInteger('is_resolved')->nullable();
            $table->dateTime('resolved_at')->nullable();
            $table->timestamps();
            $table->integer('role_id')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persistent_alerts');
    }
};