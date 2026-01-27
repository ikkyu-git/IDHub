<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PasswordHistoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Populates the password_histories table with initial data.
     */
    public function run()
    {
        DB::table('password_histories')->insert([
            [
                'id' => 4,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'password' => '$2y$12$ES7a1/z/Y.bcbzTfqwPBgOV6LTO1alNycv7kbnSh6VKo0MnXXy94a',
                'created_at' => '2026-01-16 03:25:22',
                'updated_at' => '2026-01-16 03:25:22'
            ],
            [
                'id' => 5,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'password' => '$2y$12$FxgI8JlHsPNKx0mrwUZ5LexnCurK9TX6bduMZvlblro3Py.1DykoW',
                'created_at' => '2026-01-16 03:48:40',
                'updated_at' => '2026-01-16 03:48:40'
            ],
            [
                'id' => 6,
                'user_id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'password' => '$2y$12$HTVBg9A2rhlLSFaX8GxSM.L6q/jainGqlHnF3EnPd1OsUPKemmKdK',
                'created_at' => '2026-01-16 03:56:13',
                'updated_at' => '2026-01-16 03:56:13'
            ]
        ]);
    }
}