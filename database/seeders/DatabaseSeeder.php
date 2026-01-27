<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            AuditLogsSeeder::class,
            CacheSeeder::class,
            PasswordHistoriesSeeder::class,
            RolesSeeder::class,
            RoleUserSeeder::class,
            SessionsSeeder::class,
            SettingsSeeder::class,
            SsoAccessTokensSeeder::class,
            SsoAuthCodesSeeder::class,
            SsoClientsSeeder::class,
            SsoConsentsSeeder::class,
            UsersSeeder::class
        ]);
    }
}