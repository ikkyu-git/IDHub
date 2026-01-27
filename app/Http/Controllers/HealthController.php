<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class HealthController extends Controller
{
    public function liveness()
    {
        return response()->json(['status' => 'ok'], 200);
    }

    public function readiness()
    {
        $status = [
            'app' => true,
            'db' => false,
            'cache' => false,
            'redis' => false,
        ];

        // Check DB
        try {
            DB::getPdo();
            $status['db'] = true;
        } catch (\Exception $e) {
            Log::warning('Health check DB failed: ' . $e->getMessage());
            $status['db'] = false;
        }

        // Check Cache (try storing a short-lived key)
        try {
            $key = 'health_check_' . time();
            Cache::put($key, 'ok', 2);
            $status['cache'] = Cache::get($key) === 'ok';
        } catch (\Exception $e) {
            Log::warning('Health check Cache failed: ' . $e->getMessage());
            $status['cache'] = false;
        }

        // Check Redis if configured and phpredis extension is available
        try {
            if (config('database.redis') && extension_loaded('redis')) {
                $pong = Redis::connection()->ping();
                $status['redis'] = ($pong === 'PONG' || $pong === '+PONG');
            }
        } catch (\Throwable $e) {
            Log::warning('Health check Redis failed: ' . $e->getMessage());
            $status['redis'] = false;
        }

        $ok = $status['db'] && $status['cache'];

        return response()->json(['status' => $ok ? 'ready' : 'not_ready', 'checks' => $status], $ok ? 200 : 503);
    }

    public function metrics()
    {
        $lines = [];
        $lines[] = "# HELP app_up Application liveness\n# TYPE app_up gauge";
        $lines[] = "app_up 1";

        // DB
        try {
            DB::getPdo();
            $lines[] = "# HELP db_up Database connectivity\n# TYPE db_up gauge";
            $lines[] = "db_up 1";
        } catch (\Throwable $e) {
            $lines[] = "# HELP db_up Database connectivity\n# TYPE db_up gauge";
            $lines[] = "db_up 0";
        }

        // Cache
        try {
            $key = 'metrics_health_' . time();
            Cache::put($key, 'ok', 2);
            $cacheUp = Cache::get($key) === 'ok' ? 1 : 0;
            $lines[] = "# HELP cache_up Cache connectivity\n# TYPE cache_up gauge";
            $lines[] = "cache_up {$cacheUp}";
        } catch (\Throwable $e) {
            $lines[] = "# HELP cache_up Cache connectivity\n# TYPE cache_up gauge";
            $lines[] = "cache_up 0";
        }

        // Redis
        try {
            if (config('database.redis') && extension_loaded('redis')) {
                $pong = Redis::connection()->ping();
                $redisUp = ($pong === 'PONG' || $pong === '+PONG') ? 1 : 0;
            } else {
                $redisUp = 0;
            }
            $lines[] = "# HELP redis_up Redis connectivity\n# TYPE redis_up gauge";
            $lines[] = "redis_up {$redisUp}";
        } catch (\Throwable $e) {
            $lines[] = "# HELP redis_up Redis connectivity\n# TYPE redis_up gauge";
            $lines[] = "redis_up 0";
        }

        $body = implode("\n", $lines) . "\n";

        return response($body, 200, ['Content-Type' => 'text/plain; version=0.0.4']);
    }
}
