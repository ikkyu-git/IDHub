<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            self::logAudit('create', $model);
        });

        static::updated(function ($model) {
            self::logAudit('update', $model);
        });

        static::deleted(function ($model) {
            self::logAudit('delete', $model);
        });
    }

    protected static function logAudit($action, $model)
    {
        if (!Auth::check()) return;

        $details = [];
        if ($action === 'update') {
            $details = [
                'old' => $model->getOriginal(),
                'new' => $model->getChanges(),
            ];
        } elseif ($action === 'create') {
            $details = ['new' => $model->toArray()];
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'details' => $details,
            'ip_address' => request()->ip(),
        ]);
    }
}
