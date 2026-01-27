<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SsoClientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Populates the sso_clients table with initial data.
     */
    public function run()
    {
        DB::table('sso_clients')->insert([
            [
                'id' => 1,
                'name' => 'Natchanon Account Hub',
                'client_id' => '5sTUMRvnAV',
                'client_secret' => 'bLTZqDyJKAPWdidccIsT9ct8WtvruE8nVvurDjph',
                'redirect_uris' => '["https:\\/\\/auth.natchanon.site\\/realms\\/nextauth\\/broker\\/oidc\\/endpoint"]',
                'created_at' => '2026-01-04 07:36:57',
                'updated_at' => '2026-01-04 07:50:22',
                'logo_uri' => null,
                'policy_uri' => null,
                'tos_uri' => null
            ],
            [
                'id' => 2,
                'name' => 'test',
                'client_id' => 'xpNXLBh3UE',
                'client_secret' => '4AI7qwogHstCIC8yoSRBCdUv9dg32gM4V1OJ3RXW',
                'redirect_uris' => '["https:\\/\\/auth.sanukkid.online\\/sso-test\\/callback"]',
                'created_at' => '2026-01-02 07:02:22',
                'updated_at' => '2026-01-07 14:12:14',
                'logo_uri' => null,
                'policy_uri' => null,
                'tos_uri' => null
            ]
        ]);
    }
}