<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SsoAuthCode extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id', 
        'client_id', 
        'user_id', 
        'scopes', 
        'nonce',
        'revoked', 
        'expires_at',
        'code_challenge',
        'code_challenge_method',
    ];
    protected $casts = ['expires_at' => 'datetime'];
}
