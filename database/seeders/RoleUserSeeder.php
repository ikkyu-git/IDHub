<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Populates the role_user table with initial data.
     */
    public function run()
    {
        DB::table('role_user')->insert([
            [
                'user_id' => '019b84ca-b580-72bc-90a3-4ea5e07df074',
                'role_id' => 2
            ],
            [
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'role_id' => 3
            ]
        ]);
    }
}