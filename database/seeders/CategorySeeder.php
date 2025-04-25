<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Teknologi',
                'description' => 'Berisi artikel-artikel seputar dunia teknologi dan inovasi.',
                'slug' => Str::slug('Teknologi'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pendidikan',
                'description' => 'Topik-topik yang membahas dunia pendidikan, tips belajar, dan kampus.',
                'slug' => Str::slug('Pendidikan'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kesehatan',
                'description' => 'Artikel seputar kesehatan fisik maupun mental.',
                'slug' => Str::slug('Kesehatan'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bisnis',
                'description' => 'Membahas tren bisnis, kewirausahaan, dan keuangan.',
                'slug' => Str::slug('Bisnis'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hiburan',
                'description' => 'Kategori untuk film, musik, dan hiburan lainnya.',
                'slug' => Str::slug('Hiburan'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
