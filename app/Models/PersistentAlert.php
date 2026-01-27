<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersistentAlert extends Model
{
    protected $table = 'persistent_alerts';

    protected $fillable = [
        'user_id', 'role_id', 'type', 'title', 'message', 'data', 'require_action', 'is_resolved', 'resolved_at'
    ];

    protected $casts = [
        'data' => 'array',
        'require_action' => 'boolean',
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
