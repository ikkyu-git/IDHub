<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PersistentAlert;
use App\Models\User;
use App\Models\Role;
use App\Models\AuditLog;

class AlertController extends Controller
{
    public function dismiss(Request $request, $id)
    {
        $alert = PersistentAlert::findOrFail($id);

        // Only owner or global (null user_id) can dismiss, or admins
        $user = Auth::user();
        if ($alert->user_id && $alert->user_id !== $user->id && !$user->hasRole('admin') && !$user->hasRole('super-admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $alert->is_resolved = true;
        $alert->resolved_at = now();
        $alert->save();

        return response()->json(['ok' => true]);
    }

    // Admin: list persistent alerts
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_roles')) {
            abort(403, 'Access Denied');
        }

        $query = PersistentAlert::query();
        if ($filter = $request->input('filter')) {
            if ($filter === 'open') $query->where('is_resolved', false);
            if ($filter === 'resolved') $query->where('is_resolved', true);
        }

        $alerts = $query->latest()->paginate(20)->withQueryString();
        $users = User::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();

        return view('admin.alerts.index', compact('alerts', 'users', 'roles'));
    }

    // Show create form on its own page
    public function create(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_roles')) {
            abort(403, 'Access Denied');
        }

        $users = User::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();

        return view('admin.alerts.create', compact('users', 'roles'));
    }

    // Admin: create persistent alert
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_roles')) {
            abort(403, 'Access Denied');
        }

        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'message' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
            'role_id' => 'nullable|exists:roles,id',
            'type' => 'nullable|in:info,success,warning,error',
            'require_action' => 'nullable|boolean',
        ]);

        $alert = PersistentAlert::create([
            'user_id' => $data['user_id'] ?? null,
            'role_id' => $data['role_id'] ?? null,
            'type' => $data['type'] ?? 'info',
            'title' => $data['title'] ?? null,
            'message' => $data['message'],
            'require_action' => !empty($data['require_action']),
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'create_persistent_alert',
            'model_type' => 'PersistentAlert',
            'model_id' => $alert->id,
            'details' => ['target_user' => $alert->user_id, 'target_role' => $alert->role_id],
            'ip_address' => $request->ip(),
        ]);

        return back()->with('alert', 'สร้างประกาศถาวรเรียบร้อยแล้ว');
    }

    // Admin: delete persistent alert
    public function destroy(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_roles')) {
            abort(403, 'Access Denied');
        }

        $alert = PersistentAlert::findOrFail($id);
        $alert->delete();

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'delete_persistent_alert',
            'model_type' => 'PersistentAlert',
            'model_id' => $id,
            'details' => [],
            'ip_address' => $request->ip(),
        ]);

        return back()->with('alert', 'ลบประกาศถาวรเรียบร้อยแล้ว');
    }
}
