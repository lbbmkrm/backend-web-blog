<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
                'excerpt' => 'Tren teknologi yang akan mendominasi tahun 2025.',
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
                'excerpt' => 'Cara belajar yang lebih fokus dan efisien.',
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
                'excerpt' => 'Mengapa kesehatan mental penting dan cara menjaganya.',
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
                'excerpt' => 'Panduan awal memulai bisnis digital.',
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
                'excerpt' => 'Ulasan singkat film-film terbaru yang wajib ditonton.',
                'slug' => Str::slug('Review Film Terkini'),
                'thumbnail' => 'film.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'category_id' => 1,
                'title' => 'Kecerdasan Buatan dalam Kehidupan Sehari-hari',
                'content' => 'Bagaimana AI sudah memengaruhi aktivitas kita sehari-hari.',
                'excerpt' => 'Peran AI dalam kehidupan modern.',
                'slug' => Str::slug('Kecerdasan Buatan dalam Kehidupan Sehari-hari'),
                'thumbnail' => 'ai-life.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'category_id' => 2,
                'title' => 'Panduan Menulis Artikel Ilmiah',
                'content' => 'Langkah-langkah menyusun artikel ilmiah yang baik dan benar.',
                'excerpt' => 'Tips dan trik menulis artikel akademik.',
                'slug' => Str::slug('Panduan Menulis Artikel Ilmiah'),
                'thumbnail' => 'artikel.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5,
                'category_id' => 3,
                'title' => 'Meditasi dan Manfaatnya',
                'content' => 'Meditasi terbukti membantu mengurangi stres dan meningkatkan fokus.',
                'excerpt' => 'Manfaat utama dari praktik meditasi harian.',
                'slug' => Str::slug('Meditasi dan Manfaatnya'),
                'thumbnail' => 'meditasi.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'category_id' => 4,
                'title' => 'Strategi Pemasaran Digital 2025',
                'content' => 'Tren dan strategi terbaru dalam pemasaran digital.',
                'excerpt' => 'Taktik pemasaran digital yang akan populer tahun depan.',
                'slug' => Str::slug('Strategi Pemasaran Digital 2025'),
                'thumbnail' => 'marketing2025.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'category_id' => 5,
                'title' => 'Film Dokumenter yang Wajib Ditonton',
                'content' => 'Rekomendasi film dokumenter dengan nilai edukasi tinggi.',
                'excerpt' => 'Pilihan dokumenter edukatif untuk memperluas wawasan.',
                'slug' => Str::slug('Film Dokumenter yang Wajib Ditonton'),
                'thumbnail' => 'dokumenter.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
