<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Role extends Model
{
    use Auditable;

    protected $fillable = ['name', 'slug', 'permissions'];

    protected $casts = [
        'permissions' => 'array',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
