<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SsoConsentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Populates the sso_consents table with initial data.
     */
    public function run()
    {
        DB::table('sso_consents')->insert([
            [
                'id' => 3,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'client_id' => 1,
                'scopes' => 'openid email profile',
                'granted_at' => '2026-01-15 07:01:12',
                'created_at' => '2026-01-15 07:01:12',
                'updated_at' => '2026-01-15 07:01:12'
            ],
            [
                'id' => 4,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'client_id' => 2,
                'scopes' => 'openid email profile',
                'granted_at' => '2026-01-15 07:25:43',
                'created_at' => '2026-01-15 07:25:43',
                'updated_at' => '2026-01-15 07:25:43'
            ]
        ]);
    }
}