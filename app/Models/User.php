<?php

namespace App\Models;

use App\Traits\Auditable; // เพิ่ม
use Illuminate\Database\Eloquent\Concerns\HasUuids; // เพิ่ม
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens; // เปลี่ยนกลับมาใช้ Passport
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage; // ต้องมีบรรทัดนี้

/**
 * App\Models\User
 *
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $username
 * @property array|null $attributes
 * @property bool $is_active
 * @property bool $must_change_password
 * @property \Illuminate\Support\Carbon|null $password_changed_at
 * @property \Illuminate\Support\Carbon|null $last_login_at
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Auditable, HasUuids, HasApiTokens; // เพิ่ม HasUuids และ HasApiTokens

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'is_admin', // เพิ่มบรรทัดนี้
        'is_active', // เพิ่มสถานะ Active
        'avatar', // เพิ่ม
        'attributes', // Custom Attributes
        'last_login_at',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'attributes' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Return a friendly full name (first + last) or fallback to name column
    public function getFullNameAttribute()
    {
        $first = $this->first_name;
        $last = $this->last_name;
        if ($first || $last) {
            return trim(($first ?? '') . ' ' . ($last ?? ''));
        }
        return $this->name;
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function passwordHistories(): HasMany
    {
        return $this->hasMany(PasswordHistory::class);
    }

    public function hasRole(string $slug): bool
    {
        return $this->roles->contains('slug', $slug);
    }

    public function hasPermission(string $permission): bool
    {
        // Fallback: ถ้าเป็น admin เก่า (is_admin = 1) ให้ถือว่ามีทุกสิทธิ์
        if ($this->is_admin) {
            return true;
        }

        foreach ($this->roles as $role) {
            if (in_array('*', $role->permissions ?? []) || in_array($permission, $role->permissions ?? [])) {
                return true;
            }
        }
        return false;
    }

    // Helper เพื่อดึง URL รูปภาพ
    public function getAvatarUrlAttribute()
    {
        return $this->avatar 
            ? Storage::url($this->avatar) 
            : null;
    }
}
