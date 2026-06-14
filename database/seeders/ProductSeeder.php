<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $seller1 = User::where('email', 'seller@toko.com')->first();
        $seller2 = User::where('email', 'seller2@toko.com')->first();

        $products = [
            // ── Produk Seller 1 ──────────────────────────────────
            [
                'user'   => $seller1,
                'name'   => 'Smartphone Samsung Galaxy A55',
                'price'  => 4500000,
                'stock'  => 25,
                'status' => 'active',
                'cats'   => [3], // Elektronik
                'desc'   => 'Smartphone flagship dengan layar AMOLED 6.6 inci.',
            ],
            [
                'user'   => $seller1,
                'name'   => 'Kemeja Formal Pria Slim Fit',
                'price'  => 185000,
                'stock'  => 50,
                'status' => 'active',
                'cats'   => [1], // Pakaian Pria
                'desc'   => 'Kemeja lengan panjang berbahan katun premium.',
            ],
            [
                'user'   => $seller1,
                'name'   => 'Blender Philips 600W ProBlend',
                'price'  => 320000,
                'stock'  => 15,
                'status' => 'active',
                'cats'   => [4], // Peralatan Rumah
                'desc'   => 'Blender dengan 5 tingkat kecepatan dan jar kaca.',
            ],

            // ── Produk Seller 2 ──────────────────────────────────
            [
                'user'   => $seller2,
                'name'   => 'Sepatu Lari Nike Air Zoom Pegasus',
                'price'  => 950000,
                'stock'  => 30,
                'status' => 'active',
                'cats'   => [6], // Olahraga
                'desc'   => 'Sepatu lari ringan dengan teknologi React foam.',
            ],
            [
                'user'   => $seller2,
                'name'   => 'Laptop Gaming Asus ROG Strix G16',
                'price'  => 18500000,
                'stock'  => 5,
                'status' => 'active',
                'cats'   => [3], // Elektronik
                'desc'   => 'Laptop gaming dengan RTX 4060 dan refresh rate 165Hz.',
            ],
            [
                'user'   => $seller2,
                'name'   => 'Dress Floral Wanita Premium',
                'price'  => 225000,
                'stock'  => 40,
                'status' => 'active',
                'cats'   => [2], // Pakaian Wanita
                'desc'   => 'Dress floral berbahan chiffon cocok untuk casual.',
            ],
        ];

        foreach ($products as $data) {
            $product = Product::create([
                'user_id'     => $data['user']->id,
                'name'        => $data['name'],
                'description' => $data['desc'],
                'price'       => $data['price'],
                'stock'       => $data['stock'],
                'status'      => $data['status'],
                // slug otomatis dari booted()
                // image null karena tidak ada foto sample
            ]);

            // Sync relasi kategori ke tabel pivot category_product
            $product->categories()->sync($data['cats']);
        }
    }
}