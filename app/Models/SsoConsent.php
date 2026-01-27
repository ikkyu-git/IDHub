<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SsoConsent extends Model
{
    protected $table = 'sso_consents';
    protected $fillable = ['user_id', 'client_id', 'scopes', 'granted_at'];
    protected $casts = ['granted_at' => 'datetime'];
}
