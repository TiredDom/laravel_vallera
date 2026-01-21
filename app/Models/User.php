<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_admin' => 'boolean',
            'announcements_last_seen_at' => 'datetime',
            'edit_requests_last_seen_at' => 'datetime',
        ];
    }

    public function isValleraAdmin(): bool
    {
        return str_ends_with($this->email, '@vallera.com') && $this->is_admin;
    }

    public function isSuperAdmin(): bool
    {
        return $this->email === 'superadmin@vallera.com';
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
}
