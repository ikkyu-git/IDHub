<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Populates the settings table with initial data.
     */
    public function run()
    {
        DB::table('settings')->insert([
            [
                'id' => 1,
                'key' => 'site_name',
                'value' => 'User Dashboard',
                'created_at' => '2026-01-07 20:58:21',
                'updated_at' => '2026-01-07 20:58:21'
            ],
            [
                'id' => 2,
                'key' => 'site_icon',
                'value' => '/storage/icons/jwLoA8fU59aIncbBgHVbI0ELo1DDLFbkwWJOzLEX.jpg',
                'created_at' => '2026-01-07 20:58:21',
                'updated_at' => '2026-01-07 20:58:21'
            ],
            [
                'id' => 3,
                'key' => 'support_email',
                'value' => 'natchanon.ikkyu@proton.me',
                'created_at' => '2026-01-07 20:58:21',
                'updated_at' => '2026-01-07 20:58:21'
            ],
            [
                'id' => 4,
                'key' => 'user_editable_fields',
                'value' => '["password"]',
                'created_at' => '2026-01-07 20:58:21',
                'updated_at' => '2026-01-07 14:18:00'
            ],
            [
                'id' => 5,
                'key' => 'allow_registration',
                'value' => 0,
                'created_at' => '2026-01-07 20:58:21',
                'updated_at' => '2026-01-07 20:58:21'
            ],
            [
                'id' => 6,
                'key' => 'force_2fa',
                'value' => 0,
                'created_at' => '2026-01-07 20:58:21',
                'updated_at' => '2026-01-07 20:58:21'
            ],
            [
                'id' => 7,
                'key' => 'password_expiry_days',
                'value' => 90,
                'created_at' => '2026-01-07 20:58:21',
                'updated_at' => '2026-01-15 07:27:54'
            ],
            [
                'id' => 8,
                'key' => 'social_login_google_enable',
                'value' => 1,
                'created_at' => '2026-01-07 20:58:21',
                'updated_at' => '2026-01-07 20:58:21'
            ],
            [
                'id' => 9,
                'key' => 'social_login_google_client_id',
                'value' => '23969209701-me24e8m9b9k0iosrnlhor0ufobc6qb22.apps.googleusercontent.com',
                'created_at' => '2026-01-07 20:58:21',
                'updated_at' => '2026-01-07 20:58:21'
            ],
            [
                'id' => 10,
                'key' => 'social_login_google_client_secret',
                'value' => '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',
                'created_at' => '2026-01-07 20:58:21',
                'updated_at' => '2026-01-07 20:58:21'
            ],
            [
                'id' => 11,
                'key' => 'social_login_facebook_enable',
                'value' => 0,
                'created_at' => '2026-01-07 20:58:21',
                'updated_at' => '2026-01-07 20:58:21'
            ],
            [
                'id' => 12,
                'key' => 'social_login_github_enable',
                'value' => 0,
                'created_at' => '2026-01-07 20:58:21',
                'updated_at' => '2026-01-07 20:58:21'
            ],
            [
                'id' => 13,
                'key' => 'social_login_line_enable',
                'value' => 0,
                'created_at' => '2026-01-07 20:58:21',
                'updated_at' => '2026-01-07 20:58:21'
            ],
            [
                'id' => 14,
                'key' => 'announcement',
                'value' => null,
                'created_at' => '2026-01-07 14:18:00',
                'updated_at' => '2026-01-07 14:18:00'
            ],
            [
                'id' => 15,
                'key' => 'social_login_facebook_client_id',
                'value' => null,
                'created_at' => '2026-01-07 14:18:00',
                'updated_at' => '2026-01-07 14:18:00'
            ],
            [
                'id' => 16,
                'key' => 'social_login_facebook_client_secret',
                'value' => null,
                'created_at' => '2026-01-07 14:18:00',
                'updated_at' => '2026-01-07 14:18:00'
            ],
            [
                'id' => 17,
                'key' => 'social_login_line_client_id',
                'value' => null,
                'created_at' => '2026-01-07 14:18:00',
                'updated_at' => '2026-01-07 14:18:00'
            ],
            [
                'id' => 18,
                'key' => 'social_login_line_client_secret',
                'value' => null,
                'created_at' => '2026-01-07 14:18:00',
                'updated_at' => '2026-01-07 14:18:00'
            ],
            [
                'id' => 19,
                'key' => 'social_login_github_client_id',
                'value' => null,
                'created_at' => '2026-01-07 14:18:00',
                'updated_at' => '2026-01-07 14:18:00'
            ],
            [
                'id' => 20,
                'key' => 'social_login_github_client_secret',
                'value' => null,
                'created_at' => '2026-01-07 14:18:00',
                'updated_at' => '2026-01-07 14:18:00'
            ]
        ]);
    }
}