<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('email');
                $table->index('username');
                $table->index('is_active');
            });
        }

        if (Schema::hasTable('audit_logs')) {
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->index('user_id');
                $table->index('action');
                $table->index(['user_id', 'created_at']);
            });
        }

        if (Schema::hasTable('sessions')) {
            Schema::table('sessions', function (Blueprint $table) {
                $table->index('user_id');
                $table->index('last_activity');
            });
        }

        if (Schema::hasTable('roles')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->unique('slug');
            });
        }

        if (Schema::hasTable('sso_clients')) {
            Schema::table('sso_clients', function (Blueprint $table) {
                $table->unique('client_id');
            });
        }

        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->unique('key');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex(['email']);
                $table->dropIndex(['username']);
                $table->dropIndex(['is_active']);
            });
        }

        if (Schema::hasTable('audit_logs')) {
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->dropIndex(['user_id']);
                $table->dropIndex(['action']);
                $table->dropIndex(['user_id', 'created_at']);
            });
        }

        if (Schema::hasTable('sessions')) {
            Schema::table('sessions', function (Blueprint $table) {
                $table->dropIndex(['user_id']);
                $table->dropIndex(['last_activity']);
            });
        }

        if (Schema::hasTable('roles')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropUnique(['slug']);
            });
        }

        if (Schema::hasTable('sso_clients')) {
            Schema::table('sso_clients', function (Blueprint $table) {
                $table->dropUnique(['client_id']);
            });
        }

        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropUnique(['key']);
            });
        }
    }
};
