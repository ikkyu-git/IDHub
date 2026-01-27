<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes to users table
        Schema::table('users', function (Blueprint $table) {
            $table->index('email');
            $table->index('username');
            $table->index('is_active');
        });

        // Add indexes to audit_logs table
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('action');
            $table->index(['user_id', 'created_at']);
        });

        // Add indexes to sessions table
        Schema::table('sessions', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('last_activity');
        });

        // Add indexes to roles table
        Schema::table('roles', function (Blueprint $table) {
            $table->unique('slug');
        });

        // Add indexes to sso_clients table
        Schema::table('sso_clients', function (Blueprint $table) {
            $table->unique('client_id');
        });

        // Add indexes to settings table
        Schema::table('settings', function (Blueprint $table) {
            $table->unique('key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->dropIndex(['username']);
            $table->dropIndex(['is_active']);
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['action']);
            $table->dropIndex(['user_id', 'created_at']);
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['last_activity']);
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique(['slug']);
        });

        Schema::table('sso_clients', function (Blueprint $table) {
            $table->dropUnique(['client_id']);
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropUnique(['key']);
        });
    }
};
