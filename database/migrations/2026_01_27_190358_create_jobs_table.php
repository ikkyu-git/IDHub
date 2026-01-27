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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue', 255)->nullable();
            $table->longText('payload')->nullable(false);
            $table->tinyInteger('attempts')->unsigned()->nullable(false);
            $table->integer('reserved_at')->unsigned()->nullable();
            $table->integer('available_at')->unsigned()->nullable(false);
            $table->integer('created_at')->unsigned()->nullable(false);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
};