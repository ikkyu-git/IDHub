<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Populates the roles table with initial data.
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'id' => 1,
                'name' => 'Admin',
                'slug' => 'admin',
                'permissions' => '["access_admin","view_users","create_users","edit_users"]',
                'created_at' => '2026-01-02 03:33:38',
                'updated_at' => '2026-01-04 08:54:24'
            ],
            [
                'id' => 2,
                'name' => 'User',
                'slug' => 'user',
                'permissions' => '[]',
                'created_at' => '2026-01-02 03:33:38',
                'updated_at' => '2026-01-02 03:33:38'
            ],
            [
                'id' => 3,
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'permissions' => '["access_admin","view_users","create_users","edit_users","delete_users","view_roles","manage_roles","manage_clients","manage_social_login","view_logs"]',
                'created_at' => '2026-01-02 03:33:38',
                'updated_at' => '2026-01-07 14:18:52'
            ]
        ]);
    }
}