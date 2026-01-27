<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Populates the users table with initial data.
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => '019b84ca-b580-72bc-90a3-4ea5e07df074',
                'name' => 'ทดสอบ ระบบ',
                'email' => 'r.todsob@sanukkid.online',
                'email_verified_at' => '2026-01-14 04:46:24',
                'password' => '$2y$12$0ZopITTJwaasiyiVUa.gEeVT9j8LF7RxIEtmIXmsDefMJAMYjfW/G',
                'is_admin' => 0,
                'remember_token' => null,
                'created_at' => '2026-01-03 16:57:15',
                'updated_at' => '2026-01-14 04:46:45',
                'avatar' => null,
                'last_login_at' => '2026-01-14 04:46:45',
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'two_factor_confirmed_at' => null,
                'must_change_password' => 0,
                'password_changed_at' => '2026-01-07 21:15:03',
                'is_active' => 1,
                'attributes' => null,
                'first_name' => 'ทดสอบ',
                'last_name' => 'ระบบ',
                'username' => 'demo'
            ],
            [
                'id' => '019b98c0-f9d5-70f6-a0d1-79e61ee8afba',
                'name' => 'ณัฐชนน พงษ์เสือ',
                'email' => 'admin@sanukkid.online',
                'email_verified_at' => '2026-01-07 21:00:03',
                'password' => '$2y$12$irY21e0jdfHxST2H20rkkuKtAD0gpYEAoy6RJpOprfbIWmCX3aujK',
                'is_admin' => 0,
                'remember_token' => null,
                'created_at' => '2026-01-07 13:59:02',
                'updated_at' => '2026-01-17 08:12:22',
                'avatar' => null,
                'last_login_at' => '2026-01-17 08:12:22',
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'two_factor_confirmed_at' => null,
                'must_change_password' => 0,
                'password_changed_at' => '2026-01-16 03:56:13',
                'is_active' => 1,
                'attributes' => '{"tutor_role":"admin","tutor_id":"1"}',
                'first_name' => 'ณัฐชนน',
                'last_name' => 'พงษ์เสือ',
                'username' => 'natchanon'
            ]
        ]);
    }
}