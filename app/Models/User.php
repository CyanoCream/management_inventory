<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username', 'password', 'name', 'email', 'role', 'is_locked'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
    ];
    public function isAdmin()
    {
        return $this->role === 'Admin';
    }
}
