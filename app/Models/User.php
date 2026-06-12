<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 
        'username',      // ⭐ BARU
        'email', 
        'password', 
        'is_admin', 
        'phone',
        'avatar',        // ⭐ BARU
        'bio',           // ⭐ BARU
        'gender',        // ⭐ BARU
        'birthdate',     // ⭐ BARU
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'is_admin'          => 'boolean',
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'birthdate'         => 'date',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}