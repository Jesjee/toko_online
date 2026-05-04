<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Pakaian Pria',      'icon' => '👔', 'sort_order' => 1],
            ['name' => 'Pakaian Wanita',    'icon' => '👗', 'sort_order' => 2],
            ['name' => 'Elektronik',        'icon' => '📱', 'sort_order' => 3],
            ['name' => 'Peralatan Rumah',   'icon' => '🏠', 'sort_order' => 4],
            ['name' => 'Makanan & Minuman', 'icon' => '🍜', 'sort_order' => 5],
            ['name' => 'Olahraga',          'icon' => '⚽', 'sort_order' => 6],
            ['name' => 'Buku & Edukasi',    'icon' => '📚', 'sort_order' => 7],
            ['name' => 'Kecantikan',        'icon' => '💄', 'sort_order' => 8],
        ];

        foreach ($categories as $cat) {
            Category::create(array_merge($cat, [
                'description' => 'Produk ' . $cat['name'],
                'is_active'   => true,
                // slug otomatis dari booted() → tidak perlu ditulis manual
            ]));
        }
    }
}