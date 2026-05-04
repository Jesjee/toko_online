<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin — hanya dibuat via seeder
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@toko.com',
            // password otomatis di-hash karena cast 'hashed'
            'password' => 'password',
            'role'     => 'admin',
        ]);

        // Seller 1
        User::create([
            'name'     => 'Toko Elektronik Jaya',
            'email'    => 'seller@toko.com',
            'password' => 'password',
            'role'     => 'seller',
        ]);

        // Seller 2
        User::create([
            'name'     => 'Toko Fashion Keren',
            'email'    => 'seller2@toko.com',
            'password' => 'password',
            'role'     => 'seller',
        ]);

        // Buyer 1
        User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'buyer@toko.com',
            'password' => 'password',
            'role'     => 'buyer',
        ]);

        // Buyer 2
        User::create([
            'name'     => 'Siti Rahma',
            'email'    => 'buyer2@toko.com',
            'password' => 'password',
            'role'     => 'buyer',
        ]);
    }
}