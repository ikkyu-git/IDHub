<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Add this line
use App\Models\User;
use App\Models\Role;
use App\Models\AuditLog;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\SsoClient; // ใช้ Model ใหม่

class AdminController extends Controller
{
    // สิทธิ์แบบละเอียด
    const PERMISSIONS = [
        'access_admin' => 'เข้าถึงหน้า Admin',
        'view_users' => 'ดูรายชื่อผู้ใช้',
        'create_users' => 'สร้างผู้ใช้',
        'edit_users' => 'แก้ไขผู้ใช้',
        'delete_users' => 'ลบผู้ใช้',
        'view_roles' => 'ดู Role',
        'manage_roles' => 'จัดการ Role (สร้าง/ลบ)',
        'manage_clients' => 'จัดการ SSO Clients',
        'manage_social_login' => 'จัดการ Social Login', // เพิ่มสิทธิ์ใหม่
        'view_logs' => 'ดู Audit Logs',
    ];

    public function dashboard()
    {
        $this->authorizePermission('access_admin');

        $stats = [
            'total_users' => User::count(),
            'admins' => User::whereHas('roles', fn($q) => $q->where('slug', 'admin')->orWhere('slug', 'super-admin'))->count(),
            'roles_count' => Role::count(),
            'active_sessions' => DB::table('sessions')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function users(Request $request)
    {
        $this->authorizePermission('view_users');

        $query = User::with('roles');
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }
        $users = $query->latest()->paginate(10)->withQueryString();
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function roles()
    {
        $this->authorizePermission('view_roles');
        $roles = Role::all();
        $allPermissions = self::PERMISSIONS;

        return view('admin.roles.index', compact('roles', 'allPermissions'));
    }

    public function logs()
    {
        $this->authorizePermission('view_logs');
        $logs = AuditLog::with('user')->latest()->paginate(20);

        return view('admin.logs.index', compact('logs'));
    }

    public function settings()
    {
        $this->authorizePermission('manage_roles');
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('admin.settings.index', compact('settings'));
    }

    public function clients()
    {
        $this->authorizePermission('manage_clients');
        $clients = SsoClient::all();

        return view('admin.clients.index', compact('clients'));
    }

    public function storeUser(Request $request)
    {
        $this->authorizePermission('create_users');

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:50', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);
        $name = trim($data['first_name'] . ' ' . ($data['last_name'] ?? ''));
        $username = isset($data['username']) && $data['username'] !== ''
            ? strtolower(trim($data['username']))
            : strtolower(explode('@', $data['email'])[0]);

        $user = User::create([
            'name' => $name,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'] ?? null,
            'username' => $username,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => 1,
        ]);
        $user->roles()->attach($data['role_id']);
        
        return redirect()->route('admin.users')->with('alert', 'เพิ่มผู้ใช้เรียบร้อยแล้ว');
    }

    public function updateUser(Request $request, User $user)
    {
        $this->authorizePermission('edit_users');

        // Prevent editing Super Admin if not Super Admin
        if ($user->hasRole('super-admin') && !Auth::user()->hasRole('super-admin')) {
            abort(403, 'คุณไม่มีสิทธิ์แก้ไข Super Admin');
        }

        $request->merge(['username' => $request->input('username') ? strtolower(trim($request->input('username'))) : null]);

        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:50|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8', // Optional password reset
            'must_change_password' => 'nullable|boolean',
        ]);

        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'] ?? null;
        $user->name = trim($data['first_name'] . ' ' . ($data['last_name'] ?? ''));
        $user->username = $data['username'] ?? $user->username;
        $user->email = $data['email'];

        // Update Force Change Password Status
        if (isset($data['must_change_password'])) {
            $user->must_change_password = (bool) $data['must_change_password'];
        } else {
            $user->must_change_password = false;
        }

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('admin.users')->with('alert', 'อัปเดตข้อมูลผู้ใช้เรียบร้อยแล้ว');
    }

    public function editAttributes(User $user)
    {
        $this->authorizePermission('edit_users');
        return view('admin.users.attributes', compact('user'));
    }

    public function updateAttributes(Request $request, User $user)
    {
        $this->authorizePermission('edit_users');

        $keys = $request->input('keys', []);
        $values = $request->input('values', []);
        
        $attributes = [];
        foreach ($keys as $index => $key) {
            if (!empty($key) && isset($values[$index])) {
                $attributes[$key] = $values[$index];
            }
        }

        $user->attributes = $attributes;
        $user->save();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'update_user_attributes',
            'details' => "Updated attributes for user: {$user->email}",
            'ip_address' => $request->ip()
        ]);

        return back()->with('alert', 'บันทึก Custom Attributes เรียบร้อยแล้ว');
    }

    public function updateUserRole(Request $request, User $user)
    {
        $this->authorizePermission('edit_users');
        
        // ป้องกันการแก้ Super Admin โดยคนอื่น หรือแก้ตัวเองจนเสียสิทธิ์
        if ($user->hasRole('super-admin') && !Auth::user()->hasRole('super-admin')) {
            abort(403, 'คุณไม่มีสิทธิ์แก้ไข Super Admin');
        }

        $request->validate(['role_id' => 'required|exists:roles,id']);
        $user->roles()->sync([$request->role_id]);
        return back()->with('alert', 'อัปเดต Role เรียบร้อยแล้ว');
    }

    public function destroyUser(User $user)
    {
        $this->authorizePermission('delete_users');

        if ($user->id === Auth::id()) return back()->withErrors(['msg' => 'ลบตัวเองไม่ได้']);
        if ($user->hasRole('super-admin')) return back()->withErrors(['msg' => 'ลบ Super Admin ไม่ได้']);

        $user->delete();
        return redirect()->route('admin.users')->with('alert', 'ลบผู้ใช้เรียบร้อยแล้ว');
    }

    public function toggleUserStatus(User $user)
    {
        $this->authorizePermission('edit_users');

        if ($user->id === Auth::id()) {
            return back()->withErrors(['msg' => 'ไม่สามารถระงับการใช้งานบัญชีตัวเองได้']);
        }

        if ($user->hasRole('super-admin')) {
            return back()->withErrors(['msg' => 'ไม่สามารถระงับการใช้งาน Super Admin ได้']);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        // ถ้าถูกระงับ ให้เตะออกจากระบบทุกที่
        if (!$user->is_active) {
            \Illuminate\Support\Facades\DB::table('sessions')->where('user_id', $user->id)->delete();
        }

        $status = $user->is_active ? 'เปิดใช้งาน' : 'ระงับการใช้งาน';
        return back()->with('alert', "{$status}ผู้ใช้ {$user->name} เรียบร้อยแล้ว");
    }

    public function storeRole(Request $request)
    {
        $this->authorizePermission('manage_roles');

        $data = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
        ]);
        Role::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'permissions' => $data['permissions'] ?? [],
        ]);
        return redirect()->route('admin.roles')->with('alert', 'สร้าง Role ใหม่เรียบร้อยแล้ว');
    }

    public function updateRole(Request $request, Role $role)
    {
        $this->authorizePermission('manage_roles');

        if (in_array($role->slug, ['super-admin', 'admin', 'user'])) {
            // อนุญาตให้แก้ Permission ได้ แต่ห้ามแก้ชื่อ/slug ของ System Role
            $data = $request->validate([
                'permissions' => 'array',
            ]);
            $role->update(['permissions' => $data['permissions'] ?? []]);
        } else {
            $data = $request->validate([
                'name' => 'required|string|unique:roles,name,' . $role->id,
                'permissions' => 'array',
            ]);
            $role->update([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'permissions' => $data['permissions'] ?? [],
            ]);
        }

        return redirect()->route('admin.roles')->with('alert', 'แก้ไข Role เรียบร้อยแล้ว');
    }

    public function destroyRole(Role $role)
    {
        $this->authorizePermission('manage_roles');

        if (in_array($role->slug, ['super-admin', 'admin', 'user'])) {
            return back()->withErrors(['msg' => 'ไม่สามารถลบ Role เริ่มต้นของระบบได้']);
        }
        if ($role->users()->exists()) {
            return back()->withErrors(['msg' => 'มีผู้ใช้งาน Role นี้อยู่ ไม่สามารถลบได้']);
        }
        $role->delete();
        return redirect()->route('admin.roles')->with('alert', 'ลบ Role เรียบร้อยแล้ว');
    }

    public function quickResetPassword(User $user)
    {
        $this->authorizePermission('edit_users');

        if ($user->hasRole('super-admin') && !Auth::user()->hasRole('super-admin')) {
            abort(403, 'ไม่สามารถรีเซ็ตรหัสผ่าน Super Admin ได้');
        }

        $newPassword = Str::random(8);
        $user->password = Hash::make($newPassword);
        $user->save();

        return back()->with('alert', "รีเซ็ตรหัสผ่านสำเร็จ! รหัสผ่านใหม่ของ {$user->name} คือ: {$newPassword}");
    }

    public function resetUserTwoFactor(User $user)
    {
        $this->authorizePermission('edit_users');

        // Prevent non-super-admin from touching super-admin
        if ($user->hasRole('super-admin') && !Auth::user()->hasRole('super-admin')) {
            return back()->withErrors(['msg' => 'คุณไม่มีสิทธิ์รีเซ็ต 2FA ให้ Super Admin']);
        }

        // Clear 2FA fields
        $user->two_factor_secret = null;
        $user->two_factor_recovery_codes = null;
        $user->two_factor_confirmed_at = null;
        $user->save();

        // Invalidate sessions so user must re-login and reconfigure 2FA
        DB::table('sessions')->where('user_id', $user->id)->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'reset_user_2fa',
            'model_type' => 'User',
            'model_id' => $user->id,
            'details' => ['note' => 'Admin reset 2FA for user'],
            'ip_address' => request()->ip(),
        ]);

        return back()->with('alert', "รีเซ็ต 2FA ให้ผู้ใช้ {$user->name} เรียบร้อยแล้ว");
    }

    public function toggleEmailVerified(User $user)
    {
        $this->authorizePermission('edit_users');

        // Prevent changing Super Admin by non-super-admin
        if ($user->hasRole('super-admin') && !Auth::user()->hasRole('super-admin')) {
            return back()->withErrors(['msg' => 'คุณไม่มีสิทธิ์แก้ไข Super Admin']);
        }

        if ($user->email_verified_at) {
            $user->email_verified_at = null;
            $action = 'unverify_email';
            $message = 'ยกเลิกการยืนยันอีเมลเรียบร้อยแล้ว';
        } else {
            $user->email_verified_at = now();
            $action = 'verify_email';
            $message = 'ยืนยันอีเมลของผู้ใช้เรียบร้อยแล้ว';
        }

        $user->save();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => 'User',
            'model_id' => $user->id,
            'details' => ['email' => $user->email],
            'ip_address' => request()->ip(),
        ]);

        return back()->with('alert', $message);
    }

    public function impersonate(User $user)
    {
        $this->authorizePermission('edit_users');

        if ($user->hasRole('super-admin')) {
            return back()->with('alert', 'ไม่สามารถเข้าสู่ระบบเป็น Super Admin ได้');
        }

        // เก็บ ID ของ Admin ตัวจริงไว้ใน Session
        session()->put('impersonator_id', Auth::id());

        Auth::login($user);

        return redirect()->route('user.dashboard');
    }

    public function stopImpersonating()
    {
        if (session()->has('impersonator_id')) {
            $originalUserId = session()->pull('impersonator_id');
            Auth::loginUsingId($originalUserId);
            return redirect()->route('admin.dashboard')->with('alert', 'ออกจากโหมดจำลองผู้ใช้เรียบร้อยแล้ว');
        }

        return back();
    }

    public function updateSettings(Request $request)
    {
        $this->authorizePermission('manage_roles'); 

        // เพิ่ม Validation ตรวจสอบไฟล์ภาพ
        $request->validate([
            'site_icon' => 'nullable|image|max:2048', // รองรับไฟล์รูปภาพ ไม่เกิน 2MB
        ]);

        // 1. บันทึกชื่อเว็บ
        if ($request->has('site_name')) {
            Setting::updateOrCreate(['key' => 'site_name'], ['value' => $request->site_name]);
        }

        // 2. บันทึกฟิลด์ที่แก้ไขได้
        $editableFields = $request->input('editable_fields', []);
        Setting::updateOrCreate(['key' => 'user_editable_fields'], ['value' => json_encode($editableFields)]);

        // 3. บันทึกไอคอนเว็บ (แก้ไขการบันทึกไฟล์)
        if ($request->hasFile('site_icon')) {
            // ลบรูปเก่าทิ้ง (ถ้ามี)
            $oldIcon = Setting::where('key', 'site_icon')->value('value');
            if ($oldIcon) {
                // ตัด /storage/ ออกเพื่อให้ได้ path จริงใน disk public
                $oldPath = str_replace('/storage/', '', $oldIcon);
                Storage::disk('public')->delete($oldPath);
            }

            // บันทึกไฟล์ลงใน folder 'icons' บน disk 'public'
            $path = $request->file('site_icon')->store('icons', 'public');
            
            // สร้าง URL สำหรับเรียกใช้งาน
            $url = '/storage/' . $path;
            
            Setting::updateOrCreate(['key' => 'site_icon'], ['value' => $url]);
        }

        // 4. บันทึกประกาศ
        if ($request->has('announcement')) {
            Setting::updateOrCreate(['key' => 'announcement'], ['value' => $request->announcement]);
        }

        // 4.1 บันทึก Password Expiry
        if ($request->has('password_expiry_days')) {
            Setting::updateOrCreate(['key' => 'password_expiry_days'], ['value' => $request->password_expiry_days]);
        }

        // 5. บันทึก Social Login Settings
        // if (Auth::user()->hasPermission('manage_social_login')) { // Permission check can be added later if needed
            $socialProviders = ['google', 'facebook', 'line', 'github'];
            
            foreach ($socialProviders as $provider) {
                $enableKey = "social_login_{$provider}_enable";
                $clientIdKey = "social_login_{$provider}_client_id";
                $clientSecretKey = "social_login_{$provider}_client_secret";
                $redirectKey = "social_login_{$provider}_redirect";

                // Enable Checkbox
                Setting::updateOrCreate(['key' => $enableKey], ['value' => $request->has($enableKey) ? '1' : '0']);
                
                // Credentials
                if ($request->has($clientIdKey)) {
                    Setting::updateOrCreate(['key' => $clientIdKey], ['value' => $request->input($clientIdKey)]);
                }
                if ($request->has($clientSecretKey)) {
                    Setting::updateOrCreate(['key' => $clientSecretKey], ['value' => $request->input($clientSecretKey)]);
                }
                if ($request->has($redirectKey)) {
                    Setting::updateOrCreate(['key' => $redirectKey], ['value' => $request->input($redirectKey)]);
                }
            }
        // }

        // 6. Security Policy
        // Always update the flags so unchecked boxes are saved as '0'
        Setting::updateOrCreate(['key' => 'allow_registration'], ['value' => $request->has('allow_registration') ? '1' : '0']);
        Setting::updateOrCreate(['key' => 'force_2fa'], ['value' => $request->has('force_2fa') ? '1' : '0']);
        if ($request->has('support_email')) {
            Setting::updateOrCreate(['key' => 'support_email'], ['value' => $request->support_email]);
        }

        return back()->with('alert', 'บันทึกการตั้งค่าเรียบร้อยแล้ว');
    }

    public function updateClient(Request $request, $id)
    {
        $this->authorizePermission('manage_clients');
        
        $client = SsoClient::findOrFail($id);
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'redirect' => 'required|url',
        ]);

        $client->update([
            'name' => $data['name'],
            'redirect_uris' => [$data['redirect']],
        ]);

        return back()->with('alert', 'อัปเดตข้อมูล Client เรียบร้อยแล้ว');
    }

    public function regenerateClientSecret($id)
    {
        $this->authorizePermission('manage_clients');
        
        $client = SsoClient::findOrFail($id);
        $newSecret = Str::random(40);
        
        $client->update([
            'client_secret' => $newSecret,
        ]);

        return back()->with('client_secret', $newSecret)->with('alert', 'สร้าง Secret ใหม่เรียบร้อยแล้ว');
    }

    public function exportUsers()
    {
        $this->authorizePermission('view_users');
        
        $filename = "users-" . date('Y-m-d-H-i') . ".csv";
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            // Add BOM for Excel to read UTF-8 correctly
            fputs($file, "\xEF\xBB\xBF"); 
            fputcsv($file, ['ID', 'Name', 'Email', 'Roles', 'Created At']);

            foreach (User::with('roles')->cursor() as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->roles->pluck('name')->join(', '),
                    $user->created_at->format('Y-m-d H:i:s')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function clearLogs()
    {
        $this->authorizePermission('manage_roles'); // ใช้สิทธิ์ระดับสูง
        AuditLog::truncate();
        return redirect()->route('admin.logs')->with('alert', 'ล้างประวัติการใช้งานทั้งหมดเรียบร้อยแล้ว');
    }

    // ลบ ClientRepository ออกจาก Parameter
    public function storeClient(Request $request)
    {
        $this->authorizePermission('manage_clients');

        $request->validate([
            'name' => 'required|max:255',
            'redirect' => 'required|url',
        ]);

        $clientId = Str::random(10);
        $clientSecret = Str::random(40);

        // สร้าง Client ใหม่ลงตาราง sso_clients
        SsoClient::create([
            'name' => $request->name,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uris' => [$request->redirect],
        ]);

        return redirect()->route('admin.clients')
            ->with('alert', 'สร้าง SSO Client เรียบร้อยแล้ว')
            ->with('client_id', $clientId)
            ->with('client_secret', $clientSecret);
    }

    public function destroyClient($id)
    {
        $this->authorizePermission('manage_clients');
        
        // ใช้ SsoClient ลบข้อมูล
        SsoClient::findOrFail($id)->delete();
        
        return redirect()->route('admin.clients')->with('alert', 'ลบ SSO Client เรียบร้อยแล้ว');
    }

    private function authorizePermission($permission)
    {
        $user = Auth::user();
        if (!$user) abort(403, 'Access Denied');

        // Auto-fix logic (เหมือนเดิม)
        if ($user->is_admin && $user->roles()->doesntExist()) {
            if (Schema::hasTable('roles')) {
                $superAdmin = Role::firstOrCreate(
                    ['slug' => 'super-admin'],
                    ['name' => 'Super Admin', 'permissions' => ['*']]
                );
                $user->roles()->attach($superAdmin);
                $user->refresh();
            }
        }

        if (!$user->hasPermission($permission)) {
            abort(403, 'Access Denied: คุณไม่มีสิทธิ์ ' . $permission);
        }
    }

    public function getUserSessions(User $user)
    {
        $this->authorizePermission('edit_users');

        $sessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                return [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address,
                    'user_agent' => $session->user_agent,
                    'last_active' => \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                    'platform' => $this->getPlatform($session->user_agent),
                    'browser' => $this->getBrowser($session->user_agent),
                ];
            });

        return response()->json($sessions);
    }

    public function destroyUserSession(User $user, $sessionId)
    {
        $this->authorizePermission('edit_users');

        DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', $user->id)
            ->delete();

        return back()->with('alert', 'เตะผู้ใช้ออกจาก Session เรียบร้อยแล้ว');
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
