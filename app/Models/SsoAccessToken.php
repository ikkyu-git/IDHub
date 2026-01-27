<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SsoAccessToken extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'client_id', 'user_id', 'scopes', 'revoked', 'expires_at'];
    protected $casts = ['expires_at' => 'datetime', 'revoked' => 'boolean'];
}
