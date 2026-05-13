<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Buku & Alat Tulis',    'icon' => '📚', 'description' => 'Buku pelajaran, novel, alat tulis, dan perlengkapan belajar'],
            ['name' => 'Pakaian',               'icon' => '👕', 'description' => 'Baju, celana, jaket, sepatu, dan aksesoris pakaian'],
            ['name' => 'Peralatan Dapur',       'icon' => '🍳', 'description' => 'Peralatan masak, peralatan makan, dan perlengkapan dapur'],
            ['name' => 'Elektronik',            'icon' => '💻', 'description' => 'Gadget, charger, kabel, dan perangkat elektronik lainnya'],
            ['name' => 'Furnitur & Perabot',   'icon' => '🪑', 'description' => 'Meja, kursi, rak, dan perabot rumah tangga'],
            ['name' => 'Perlengkapan Belajar', 'icon' => '🎒', 'description' => 'Tas, lampu belajar, stationery, dan perlengkapan studi'],
            ['name' => 'Peralatan Rumah',      'icon' => '🔧', 'description' => 'Kipas angin, ember, sapu, dan peralatan rumah tangga'],
            ['name' => 'Lainnya',              'icon' => '📦', 'description' => 'Barang-barang lain yang masih layak pakai'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => Str::slug($cat['name'])],
                array_merge($cat, ['slug' => Str::slug($cat['name'])])
            );
        }
    }
}
