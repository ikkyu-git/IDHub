<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SsoAuthCode;
use App\Models\SsoAccessToken;
use App\Models\SsoRefreshToken;
use Carbon\Carbon;

class PruneSsoTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sso:prune';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune expired SSO tokens and auth codes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $codes = SsoAuthCode::where('expires_at', '<', $now)->delete();
        $accessTokens = SsoAccessToken::where('expires_at', '<', $now)->delete();
        $refreshTokens = SsoRefreshToken::where('expires_at', '<', $now)->delete();

        $this->info("Pruned: $codes codes, $accessTokens access tokens, $refreshTokens refresh tokens.");
    }
}
