<?php

namespace Database\Seeders;

use Exception;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('blogs')->insert([
            [
                'user_id' => 1,
                'category_id' => 1,
                'title' => 'Tren Teknologi 2025',
                'content' => 'Konten lengkap tentang tren teknologi masa depan...',
                'description' => 'Ringkasan tentang tren teknologi terkini.',
                'slug' => Str::slug('Tren Teknologi 2025'),
                'thumbnail' => 'tech2025.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'category_id' => 2,
                'title' => 'Tips Belajar Efektif',
                'content' => 'Berbagai tips belajar untuk mahasiswa dan pelajar.',
                'description' => 'Cara belajar efektif dan efisien.',
                'slug' => Str::slug('Tips Belajar Efektif'),
                'thumbnail' => 'belajar.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'category_id' => 3,
                'title' => 'Menjaga Kesehatan Mental',
                'content' => 'Pentingnya menjaga kesehatan mental di tengah kesibukan.',
                'description' => 'Kesehatan mental itu penting, ini alasannya.',
                'slug' => Str::slug('Menjaga Kesehatan Mental'),
                'thumbnail' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'category_id' => 4,
                'title' => 'Cara Membangun Bisnis Online',
                'content' => 'Langkah-langkah praktis untuk memulai bisnis online.',
                'description' => 'Bisnis online semakin populer, ini caranya.',
                'slug' => Str::slug('Cara Membangun Bisnis Online'),
                'thumbnail' => 'bisnis.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5,
                'category_id' => 5,
                'title' => 'Review Film Terkini',
                'content' => 'Review jujur film terbaru yang tayang bulan ini.',
                'description' => 'Ulasan film terbaru dari berbagai genre.',
                'slug' => Str::slug('Review Film Terkini'),
                'thumbnail' => 'film.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
