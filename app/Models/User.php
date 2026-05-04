<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',      // -- tambahkan
        'phone',     // -- tambahkan
        'address',   // -- tambahkan
        'avatar',    // -- tambahkan
        'is_active', // -- tambahkan
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // -- hash otomatis saat assign
            'is_active' => 'boolean',
        ];
    }

    // --- Helper Method Role ---
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isSeller(): bool { return $this->role === 'seller'; }
    public function isBuyer(): bool { return $this->role === 'buyer'; }

    // --- Relasi (akan dipakai di pertemuan berikutnya) ---
    public function products() { return $this->hasMany(Product::class); }
    public function orders() { return $this->hasMany(Order::class); }
    public function cartItems() { return $this->hasMany(CartItem::class); }
}