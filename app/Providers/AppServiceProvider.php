<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::tokensCan([
            'view-user' => 'ดูข้อมูลส่วนตัว (View your profile info)',
            'view-email' => 'ดูอีเมล (View your email address)',
            'edit-user' => 'แก้ไขข้อมูลส่วนตัว (Edit your profile)',
            'admin-access' => 'เข้าถึงระบบผู้ดูแล (Access admin features)',
        ]);

        Passport::setDefaultScope([
            'view-user',
        ]);

        RateLimiter::for('sso-api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Load Social Login Settings from DB
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $settings = \App\Models\Setting::where('key', 'like', 'social_login_%')->pluck('value', 'key');
                
                $providers = ['google', 'facebook', 'line', 'github'];
                foreach ($providers as $provider) {
                    if (($settings["social_login_{$provider}_enable"] ?? '0') === '1') {
                        config([
                            "services.{$provider}" => [
                                'client_id' => $settings["social_login_{$provider}_client_id"] ?? '',
                                'client_secret' => $settings["social_login_{$provider}_client_secret"] ?? '',
                                'redirect' => url("/login/{$provider}/callback"),
                            ]
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            // Ignore if DB not ready
        }
    }
}
