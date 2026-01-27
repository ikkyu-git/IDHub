<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Setting;
use App\Models\AuditLog; // เพิ่ม import

use App\Models\SsoAccessToken;
use App\Models\SsoRefreshToken;
use App\Models\SsoConsent;

class UserController extends Controller
{
    public function authorizedApps()
    {
        // ดึง Token ทั้งหมดที่ยังไม่หมดอายุและยังไม่ถูกยกเลิก
        $apps = SsoAccessToken::where('user_id', Auth::id())
            ->where('revoked', false)
            ->where('expires_at', '>', now())
            ->join('sso_clients', 'sso_access_tokens.client_id', '=', 'sso_clients.id')
            ->select(
                'sso_clients.id as client_db_id',
                'sso_clients.name as client_name',
                'sso_access_tokens.created_at',
                'sso_access_tokens.scopes'
            )
            ->orderBy('sso_access_tokens.created_at', 'desc')
            ->get()
            ->groupBy('client_db_id'); // จัดกลุ่มตาม Client ID

        return view('user.authorized-apps', ['apps' => $apps]);
    }

    public function revokeApp($clientId)
    {
        // หา Token ทั้งหมดของ User นี้ ที่เป็นของ Client นี้
        $tokens = SsoAccessToken::where('client_id', $clientId)
            ->where('user_id', Auth::id())
            ->get();

        if ($tokens->isEmpty()) {
            return back()->with('error', 'ไม่พบข้อมูลแอปพลิเคชัน');
        }

        foreach ($tokens as $token) {
            $token->update(['revoked' => true]);
            // Revoke associated refresh tokens
            SsoRefreshToken::where('access_token_id', $token->id)->update(['revoked' => true]);
        }

        // Also remove any stored consent for this client for the user
        try {
            SsoConsent::where('client_id', $clientId)->where('user_id', Auth::id())->delete();
        } catch (\Throwable $e) {
            // ignore
        }

        return back()->with('success', 'ยกเลิกการเชื่อมต่อและออกจากระบบทุกอุปกรณ์เรียบร้อยแล้ว');
    }

    public function dashboard()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        
        // ดึงประวัติการเข้าสู่ระบบล่าสุด 5 รายการ
        $loginActivities = AuditLog::where('user_id', Auth::id())
            ->whereIn('action', ['login_success', 'login_failed', 'logout'])
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', [
            'user' => Auth::user(), 
            'settings' => $settings,
            'loginActivities' => $loginActivities
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        // ดึงค่า Config ว่าอนุญาตให้แก้อะไรบ้าง
        $settings = Setting::pluck('value', 'key')->toArray();
        $editable = json_decode($settings['user_editable_fields'] ?? '["name","email","password","avatar"]', true);

        // 1. ตรวจสอบข้อมูล (Validation)
        $rules = [];
        if (in_array('name', $editable)) $rules['name'] = ['required', 'string', 'max:255'];
        if (in_array('email', $editable)) $rules['email'] = ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id];
        if (in_array('password', $editable)) $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        if (in_array('avatar', $editable)) $rules['avatar'] = ['nullable', 'image', 'max:2048'];

        $validated = $request->validate($rules);

        // 2. อัปเดตข้อมูลตามสิทธิ์
        if (in_array('name', $editable) && isset($validated['name'])) $user->name = $validated['name'];
        if (in_array('email', $editable) && isset($validated['email'])) $user->email = $validated['email'];

        // 3. เปลี่ยนรหัสผ่าน
        if (in_array('password', $editable) && $request->filled('password')) {
            // Check Password History
            $previousPasswords = \App\Models\PasswordHistory::where('user_id', $user->id)
                ->latest()
                ->take(3)
                ->get();

            foreach ($previousPasswords as $history) {
                if (Hash::check($validated['password'], $history->password)) {
                    return back()->withErrors(['password' => 'คุณไม่สามารถใช้รหัสผ่านซ้ำกับ 3 ครั้งล่าสุดได้']);
                }
            }

            // Save old password to history
            \App\Models\PasswordHistory::create([
                'user_id' => $user->id,
                'password' => $user->password,
            ]);

            $user->password = Hash::make($validated['password']);
            $user->password_changed_at = now();
        }

        // 4. อัปโหลดรูปโปรไฟล์
        if (in_array('avatar', $editable) && $request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return back()->with('alert', 'บันทึกข้อมูลโปรไฟล์สำเร็จ');
    }

    public function loginHistory()
    {
        $logs = AuditLog::where('user_id', Auth::id())
            ->whereIn('action', ['login_success', 'login_failed', 'logout', 'login_locked_out'])
            ->latest()
            ->paginate(20);

        return view('user.login-history', compact('logs'));
    }

    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed|different:current_password',
        ]);

        $user = Auth::user();

        // Check Password History (Last 3 passwords)
        $previousPasswords = \App\Models\PasswordHistory::where('user_id', $user->id)
            ->latest()
            ->take(3)
            ->get();

        foreach ($previousPasswords as $history) {
            if (Hash::check($request->password, $history->password)) {
                return back()->withErrors(['password' => 'คุณไม่สามารถใช้รหัสผ่านซ้ำกับ 3 ครั้งล่าสุดได้']);
            }
        }

        // Save current password to history before updating
        \App\Models\PasswordHistory::create([
            'user_id' => $user->id,
            'password' => $user->password, // Save the OLD password hash
        ]);

        $user->password = Hash::make($request->password);
        $user->must_change_password = false;
        $user->password_changed_at = now();
        $user->save();
      

        // Check if there's an intended URL to return to (e.g., from OIDC flow)
        $intendedUrl = session('url.password_change_intended');
        session()->forget('url.password_change_intended');

        if ($intendedUrl) {
            return redirect($intendedUrl)->with('alert', 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว');
        }

        return redirect()->route('user.dashboard')->with('alert', 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว');
    }
}
