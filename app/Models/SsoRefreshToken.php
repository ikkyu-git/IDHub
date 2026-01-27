<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SsoRefreshToken extends Model
{
    protected $table = 'sso_refresh_tokens';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'access_token_id',
        'revoked',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'revoked' => 'boolean',
    ];
}
