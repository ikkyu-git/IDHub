<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
    public function index()
    {
        $sessions = DB::table('sessions')
            ->where('user_id', Auth::id())
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                return (object) [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address,
                    'user_agent' => $session->user_agent,
                    'is_current_device' => $session->id === session()->getId(),
                    'last_active' => \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                    'platform' => $this->getPlatform($session->user_agent),
                    'browser' => $this->getBrowser($session->user_agent),
                ];
            });

        return view('user.sessions', compact('sessions'));
    }

    public function destroy($sessionId)
    {
        DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', Auth::id())
            ->delete();

        return back()->with('success', 'ออกจากระบบอุปกรณ์ดังกล่าวเรียบร้อยแล้ว');
    }

    public function destroyAllOthers()
    {
        DB::table('sessions')
            ->where('user_id', Auth::id())
            ->where('id', '!=', session()->getId())
            ->delete();

        return back()->with('success', 'ออกจากระบบทุกอุปกรณ์อื่นเรียบร้อยแล้ว');
    }

    private function getPlatform($userAgent)
    {
        if (preg_match('/windows/i', $userAgent)) return 'Windows';
        if (preg_match('/macintosh|mac os x/i', $userAgent)) return 'macOS';
        if (preg_match('/linux/i', $userAgent)) return 'Linux';
        if (preg_match('/android/i', $userAgent)) return 'Android';
        if (preg_match('/iphone|ipad|ipod/i', $userAgent)) return 'iOS';
        return 'Unknown';
    }

    private function getBrowser($userAgent)
    {
        if (preg_match('/MSIE/i', $userAgent) && !preg_match('/Opera/i', $userAgent)) return 'Internet Explorer';
        if (preg_match('/Firefox/i', $userAgent)) return 'Firefox';
        if (preg_match('/Chrome/i', $userAgent)) return 'Chrome';
        if (preg_match('/Safari/i', $userAgent)) return 'Safari';
        if (preg_match('/Opera/i', $userAgent)) return 'Opera';
        return 'Unknown';
    }
}
