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
        Schema::create('users', function (Blueprint $table) {
            $table->string('id', 255)->primary();
            $table->string('name', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->dateTime('email_verified_at')->nullable();
            $table->string('password', 255)->nullable();
            $table->tinyInteger('is_admin')->nullable();
            $table->string('remember_token', 255)->nullable();
            $table->timestamps();
            $table->string('avatar', 255)->nullable();
            $table->dateTime('last_login_at')->nullable();
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->dateTime('two_factor_confirmed_at')->nullable();
            $table->tinyInteger('must_change_password')->nullable();
            $table->dateTime('password_changed_at')->nullable();
            $table->tinyInteger('is_active')->nullable();
            $table->text('attributes')->nullable();
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255)->nullable();
            $table->string('username', 255)->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};