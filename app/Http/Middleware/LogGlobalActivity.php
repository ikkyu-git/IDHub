<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class LogGlobalActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // ข้ามการบันทึกสำหรับ debugbar หรือ assets ถ้ามี
        if ($request->is('_debugbar*', 'css*', 'js*', 'images*')) {
            return $response;
        }

        try {
            AuditLog::create([
                'user_id' => Auth::id(), // บันทึก User ID ถ้าล็อกอินแล้ว (หรือ null)
                'action' => $request->method() . ' ' . $request->path(),
                'model_type' => 'System', // ระบุว่าเป็น System Log
                'model_id' => null,
                'details' => [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'inputs' => $request->except(['password', 'password_confirmation', '_token']), // เก็บ Input ทั้งหมด (ยกเว้นรหัสผ่าน)
                    'status_code' => $response->getStatusCode(),
                ],
                'ip_address' => $request->ip(),
            ]);
        } catch (\Exception $e) {
            // ป้องกันไม่ให้ Error จากการ Log ทำให้ระบบหลักล่ม
        }

        return $response;
    }
}
