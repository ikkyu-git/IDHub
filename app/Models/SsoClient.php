<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SsoClient extends Model
{
    protected $fillable = ['name', 'client_id', 'client_secret', 'redirect_uris', 'logo_uri', 'policy_uri', 'tos_uri'];

    protected $casts = [
        'redirect_uris' => 'array',
    ];

    public function getRedirectAttribute()
    {
        return $this->redirect_uris[0] ?? '';
    }
}
